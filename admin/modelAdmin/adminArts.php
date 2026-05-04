<?php
class adminArts {
    private static function clean($value){
        return trim($value);
    }


    public static function getAllArts() {
        $query = "SELECT a.id AS art_id,
                         al.title AS art_title,
                         u.username AS author,
                         cl.name AS cat_name,
                         a.in_shop,
                         ai.image AS art_image,
                         a.is_deleted
                  FROM arts a
                  JOIN users u ON a.user_id = u.id
                  LEFT JOIN art_lang al
                    ON al.art_id = a.id
                    AND al.lang_id = (
                        SELECT id FROM languages WHERE code = 'ee' LIMIT 1
                    )
                  LEFT JOIN cat_lang cl
                    ON cl.cat_id = a.category_id
                    AND cl.lang_id = (
                        SELECT id FROM languages WHERE code = 'ee' LIMIT 1
                    )
                  LEFT JOIN art_images ai 
                    ON ai.art_id = a.id
                    AND ai.position = 0
                  ORDER BY a.id DESC";
                  
        $db = new Database();
        return $db->getAll($query);
    }

    public static function getCategoriesAndAuthors() {
      $db = new Database();

      $categories = $db->getAll("
        SELECT c.id AS cat_id,
          cl.name AS cat_name
        FROM categories c
        JOIN cat_lang cl ON cl.cat_id = c.id
        JOIN languages l ON cl.lang_id = l.id
        WHERE l.code = 'ee'  
      ");

      $authors = $db->getAll("
        SELECT u.id AS author_id,
          u.username AS author_name
        FROM users u
        WHERE u.id <> 1
      ");

      return [
        'categories' => $categories,
        'authors' => $authors
      ];
    }

    public static function getArtById($id) {
        $id = (int)$id;

        if($id <= 0) {
            return false;
        }

        $db = new Database();

        $art = $db->getOne("
            SELECT a.*, 
                u.username,
                c.id as category_id
            FROM arts a
            JOIN users u ON u.id = a.user_id
            JOIN categories c ON c.id = a.category_id
            WHERE a.id = ?
        ", [$id]);

        $langs = $db->getAll("
            SELECT l.code, al.title, al.text
            FROM art_lang al
            JOIN languages l ON l.id = al.lang_id
            WHERE al.art_id = ?
        ", [$id]);

        $images = $db->getAll("
            SELECT id, image, position
            FROM art_images
            WHERE art_id = ?
            ORDER BY position ASC
        ", [$id]);

        return [
            'art' => $art,
            'langs' => $langs,
            'images' => $images
        ];
    }

    public static function addArt() {
        $controll = array(0 => false, 1 => 'error');
        if (isset($_POST['save'])) {

            $errorString = "";

            $categoryName = self::clean($_POST['category']);
            $categoryName = preg_replace('/[^a-zA-Z0-9_-]/','',$categoryName);
            $authorName = self::clean($_POST['author']);
            $inShop = isset($_POST['in_shop']) ? 1 : 0;

            $price = !empty($_POST['price']) ? (float)$_POST['price'] : null;

            $title_ee = self::clean($_POST['title_ee']);
            $title_en = self::clean($_POST['title_en']);
            $title_ru = self::clean($_POST['title_ru']);

            $desc_ee = !empty($_POST['desc_ee']) ? trim($_POST['desc_ee']) : null;
            $desc_en = !empty($_POST['desc_en']) ? trim($_POST['desc_en']) : null;
            $desc_ru = !empty($_POST['desc_ru']) ? trim($_POST['desc_ru']) : null;

            if (!$title_ee || !$title_en || !$title_ru) {
                $errorString .= "Kõik pealkirjad peavad olema täidetud.<br>";
            }

            $hasFiles = !empty($_FILES['images']['name'][0]);
            if (!$hasFiles) $errorString .= "Vähemalt üks pilt on nõutav.<br>";

            $db = new Database();

            $category = $db->getOne("SELECT cat_id FROM cat_lang WHERE name = ? LIMIT 1", [$categoryName]);
            if (!$category) {
                $errorString .= "Kategooriat ei leitud.<br>";
            } else {
                $categoryId = $category['cat_id'];
            }

            $author = $db->getOne("SELECT id FROM users WHERE username = ? LIMIT 1", [$authorName]);
            if (!$author) {
                $errorString .= "Autorit ei leitud.<br>";
            } else {
                $userId = $author['id'];
            }

            if (mb_strlen($errorString) == 0) {
                $db->beginTransaction();
                try {
                   
                    $db->executeRun("INSERT INTO arts (`category_id`, `price`, `in_shop`, `user_id`) 
                                    VALUES (?,?,?,?)",[$categoryId, $price, $inShop, $userId]);
                    $artId = $db->getLastId();

                    
                    $db->executeRun("INSERT INTO art_lang (`art_id`, `lang_id`, `title`, `text`)   
                        VALUES (?,?,?,?)",[$artId, 1, $title_ee, $desc_ee]);

                    $db->executeRun("INSERT INTO art_lang (`art_id`, `lang_id`, `title`, `text`)
                        VALUES (?,?,?,?)", [$artId, 2, $title_en, $desc_en]);

                    $db->executeRun("INSERT INTO art_lang (`art_id`, `lang_id`, `title`, `text`)
                        VALUES (?,?,?,?)", [$artId, 3, $title_ru, $desc_ru]);

                    $baseDir = dirname(__DIR__, 2) . '/public/images/arts/';
                    $categoryDir = $baseDir . $categoryName . '/';
                    if (!is_dir($categoryDir)) mkdir($categoryDir, 0755, true);

                    $artDir = $categoryDir . $artId . '/';
                    if (!is_dir($artDir)) mkdir($artDir, 0755, true);

                    if ($hasFiles) {
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'jfif'];
                        $maxSize = 2 * 1024 * 1024; 
                        $images = $_FILES['images'];
                        $positionOrder = isset($_POST['image_order'])
                                        ? explode(',', $_POST['image_order'])
                                        : range(0, count($images['name']) - 1);

                        foreach ($positionOrder as $position => $i) {
                            if (!isset($images['name'][$i])) continue;

                            if ($images['error'][$i] !== UPLOAD_ERR_OK) {
                                throw new Exception("Viga faili üleslaadimisel: " . $images['name'][$i]);
                            }

                            $tmpName = $images['tmp_name'][$i];
                            
                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                            $mime = finfo_file($finfo, $tmpName);
                            finfo_close($finfo);

                            $allowedMime = ['image/jpeg', 'image/png'];

                            if(!in_array($mime,$allowedMime)){
                                throw new Exception("Fail ei ole kehtiv pilt.");
                            }
                            
                            $ext = strtolower(pathinfo($images['name'][$i], PATHINFO_EXTENSION));
                            $size = filesize($tmpName);

                            if (!in_array($ext, $allowedExtensions)) {
                                throw new Exception("Toetamata pildivorming: " . $images['name'][$i]);
                            }
                            if ($size > $maxSize) {
                                throw new Exception("Fail on liiga suur (maksimaalselt 2 MB): " . $images['name'][$i]);
                            }

                            $filename = bin2hex(random_bytes(16)) . '.' . $ext;

                            if(!is_uploaded_file($tmpName)){
                                throw new Exception("Kehtetu üleslaadimiskatse.");
                            }

                            move_uploaded_file($tmpName, $artDir . $filename);
                            chmod($artDir.$filename,0644);

                            $db->executeRun("
                                INSERT INTO art_images (art_id, image, position)
                                VALUES (?,?,?)
                            ", [$artId, "images/arts/$categoryName/$artId/$filename", $position]);
                        }
                    }

                    $db->commit();
                    $controll = array(0 => true);

                } catch (Exception $e) {
                    $db->rollBack();

                    if (isset($artDir) && is_dir($artDir)) {
                        $files = glob($artDir . '*', GLOB_MARK);
                        foreach ($files as $f) unlink($f);
                        rmdir($artDir);
                    }
                    $controll = array(0 => false, 1 => $e->getMessage());
                }
            } else {
                $controll = array(0 => false, 1 => $errorString);
            }
        }

        return $controll;
    }

    public static function editArt($id) {
        $controll = array(0 => false, 1 => 'error');

        if(isset($_POST['save'])){

            $errorString = "";

            $categoryName = self::clean($_POST['category']);
            $authorName = self::clean($_POST['author']);
            $inShop = isset($_POST['in_shop']) ? 1 : 0;
            $price = ($_POST['price'] !== "") ? (float)$_POST['price'] : null;

            $title_ee = self::clean($_POST['title_ee']);
            $title_en = self::clean($_POST['title_en']);
            $title_ru = self::clean($_POST['title_ru']);

            $desc_ee = !empty($_POST['desc_ee']) ? trim($_POST['desc_ee']) : null;
            $desc_en = !empty($_POST['desc_en']) ? trim($_POST['desc_en']) : null;
            $desc_ru = !empty($_POST['desc_ru']) ? trim($_POST['desc_ru']) : null;

            if (!$title_ee || !$title_en || !$title_ru) {
                $errorString .= "Kõik pealkirjad peavad olema täidetud<br>";
            }

            $db = new Database();

            $category = $db->getOne("SELECT cat_id FROM cat_lang WHERE name=? LIMIT 1", [$categoryName]);
            if (!$category) {
                $errorString .= "Kategooriat ei leitud<br>";
            } else {
                $categoryId = $category['cat_id'];
            }

            $author = $db->getOne("SELECT id FROM users WHERE username=? LIMIT 1", [$authorName]);
            if (!$author) {
                $errorString .= "Autorit ei leitud<br>";
            } else {
                $userId = $author['id'];
            }
            
            if (mb_strlen($errorString) == 0) {
                $db->beginTransaction();
                try {
                    $oldArt = $db->getOne("
                        SELECT c.id as cat_id,
                            cl.name as cat_name
                        FROM arts a
                        JOIN categories c ON c.id = a.category_id
                        JOIN cat_lang cl ON cl.cat_id = c.id
                        JOIN languages l ON l.id = cl.lang_id
                        WHERE a.id = ? AND l.code='ee'
                    ", [$id]);

                    $oldCategoryName = $oldArt['cat_name'];

                    $db->executeRun("
                        UPDATE arts
                        SET category_id=?,
                            price=?,
                            in_shop=?,
                            user_id=?
                        WHERE id=?
                    ", [$categoryId, $price, $inShop, $userId, $id]);

                    if ($oldCategoryName !== $categoryName) {

                        $baseDir = dirname(__DIR__,2)."/public/images/arts/";
                        $oldPath = $baseDir.$oldCategoryName."/".$id;
                        $newPath = $baseDir.$categoryName."/".$id;

                        if (!is_dir($baseDir.$categoryName)) {
                            mkdir($baseDir.$categoryName,0755,true);
                        }

                        if (is_dir($oldPath)) {
                            rename($oldPath,$newPath);
                            $db->executeRun("
                                UPDATE art_images
                                SET image = REPLACE(image,?,?)
                                WHERE art_id=?
                            ", [
                                "images/arts/$oldCategoryName/$id/",
                                "images/arts/$categoryName/$id/",
                                $id
                                ]);
                        }
                    }

                    $db->executeRun("UPDATE art_lang SET title=?, text=? WHERE art_id=? AND lang_id=1", [$title_ee, $desc_ee, $id]);
                    $db->executeRun("UPDATE art_lang SET title=?, text=? WHERE art_id=? AND lang_id=2", [$title_en, $desc_en, $id]);
                    $db->executeRun("UPDATE art_lang SET title=?, text=? WHERE art_id=? AND lang_id=3", [$title_ru, $desc_ru, $id]);

                    $baseDir = dirname(__DIR__,2)."/public/images/arts/";
                    $categoryDir = $baseDir.$categoryName."/";
                    if (!is_dir($categoryDir)) mkdir($categoryDir,0755,true);

                    $artDir = $categoryDir.$id."/";
                    if (!is_dir($artDir)) mkdir($artDir,0755,true);

                    $deleteIds = $_POST['delete_images'] ?? [];
                    $existingImagesCount = $db->getOne("SELECT COUNT(*) as cnt FROM art_images WHERE art_id=?", [$id])['cnt'];
                    $newFilesCount = !empty($_FILES['images']['name'][0]) ? count(array_filter($_FILES['images']['name'])) : 0;
                    $remainingCount = $existingImagesCount - count($deleteIds) + $newFilesCount;

                    if ($remainingCount <= 0) {
                        throw new Exception("Teosel peab olema vähemalt üks pilt.");
                    }

                    if (!empty($deleteIds)) {
                        foreach ($deleteIds as $delId) {
                            $imgData = $db->getOne("SELECT image FROM art_images WHERE id=?", [$delId]);
                            if ($imgData) {
                                $imgPath = dirname(__DIR__,2) . '/public/' . $imgData['image'];
                                if (file_exists($imgPath)) unlink($imgPath);
                            }
                            $db->executeRun("DELETE FROM art_images WHERE id=?", [$delId]);
                        }
                    }

                    if (!empty($_FILES['images']['name'][0])) {

                        $images = $_FILES['images'];
                        $positionOrder = isset($_POST['image_order']) ? explode(',', $_POST['image_order']) : [];

                        $allowedTypes = ['jpg','jpeg','png', 'jfif'];
                        $maxSize = 2 * 1024 * 1024;

                        foreach ($positionOrder as $position => $idx) {
                            if (strpos($idx, 'n-') === 0) {
                                $i = intval(substr($idx, 2));
                                if (isset($images['name'][$i]) && $images['error'][$i] === UPLOAD_ERR_OK) {
                                    
                                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                    $mime = finfo_file($finfo,$images['tmp_name'][$i]);
                                    finfo_close($finfo);

                                    if(!in_array($mime,['image/jpeg', 'image/png'])){
                                        throw new Exception("Fail ei ole kehtiv pilt.");
                                    }

                                    if ($images['size'][$i] > $maxSize) {
                                        throw new Exception("Fail on liiga suur (maksimaalselt 2 MB): " . $images['name'][$i]);
                                    }

                                    $ext = strtolower(pathinfo($images['name'][$i], PATHINFO_EXTENSION));
                                    if (!in_array($ext, $allowedTypes)) {
                                        throw new Exception("Toetamata pildivorming: " . $images['name'][$i]);
                                    }

                                    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
                                    move_uploaded_file($images['tmp_name'][$i], $artDir . $filename);
                                    chmod($artDir.$filename,0644);

                                    $db->executeRun("
                                        INSERT INTO art_images (art_id, image, position)
                                        VALUES (?,?,?)
                                    ", [$id, "images/arts/$categoryName/$id/$filename", $position]);
                                }
                            }
                        }
                    }

                    if (!empty($_POST['image_order'])) {
                        $positionOrder = explode(',', $_POST['image_order']);
                        foreach ($positionOrder as $position => $idx) {
                            if (strpos($idx, 'e-') === 0) {
                                $imgId = intval(substr($idx, 2));
                                $db->executeRun("UPDATE art_images SET position=? WHERE id=?", [$position, $imgId]);
                            }
                        }
                    }

                    $images = $db->getAll("SELECT id FROM art_images WHERE art_id=? ORDER BY position ASC", [$id]);
                    foreach ($images as $pos => $img) {
                        $db->executeRun("UPDATE art_images SET position=? WHERE id=?", [$pos, $img['id']]);
                    }

                    $db->commit();
                    $controll = array(0 => true);

                } catch (Exception $e) {
                    $db->rollBack();
                    $controll = array(0 => false, 1 => $e->getMessage());
                }
            } else {
                $controll = array(0 => false, 1 => $errorString);
            }
        }

        return $controll;
    }

    public static function deleteArt($id) {
        $controll = array(0 => false, 1 => 'error');

        if(isset($_POST['delete'])){
            $db = new Database();
            $db->beginTransaction();

            try {
                $art = $db->getOne("
                    SELECT cl.name as cat_name
                    FROM arts a
                    JOIN categories c ON c.id = a.category_id
                    JOIN cat_lang cl ON cl.cat_id = c.id
                    JOIN languages l ON l.id = cl.lang_id
                    WHERE a.id=? AND l.code='ee'
                ", [$id]);

                if(!$art) throw new Exception("Artwork not found");

                $categoryName = $art['cat_name'];

                $artDir = dirname(__DIR__,2)."/public/images/arts/".$categoryName."/".$id."/";
                if(is_dir($artDir)){
                    $files = glob($artDir."*");
                    foreach($files as $file){
                        if(is_file($file)) unlink($file);
                    }
                    rmdir($artDir);
                }

                $db->executeRun("DELETE FROM art_images WHERE art_id=?", [$id]);
                $db->executeRun("DELETE FROM art_lang WHERE art_id=?", [$id]);
                $db->executeRun("DELETE FROM arts WHERE id=?", [$id]);

                $db->commit();
                $controll = array(0 => true);

            } catch (Exception $e) {
                $db->rollBack();
                $controll = array(0 => false, 1 => $e->getMessage());
            }
        }

        return $controll;
    }

    public static function getDeletedStatus($id) {
        $db = new Database();

        $query = "SELECT a.is_deleted FROM arts a WHERE id = ?";

        return $db->getOne($query,[$id])['is_deleted'];
    }

    public static function toggleDeleted($id) {
        $db = new Database();

        $current = self::getDeletedStatus($id);
        $new = $current ? 0 : 1;

        $query = "UPDATE arts SET is_deleted = ? WHERE id = ?";

        $db->executeRun($query, [$new, $id]);

        return $new;
    }
}