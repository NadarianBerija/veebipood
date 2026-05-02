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
                         ai.image AS art_image
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
}