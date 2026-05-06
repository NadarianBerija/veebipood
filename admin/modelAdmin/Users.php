<?php
class Users {
    public static function getAllUsers() {
        $query = "SELECT u.id AS user_id,
                         u.username AS user_name,
                         u.picture AS picture,
                         u.status AS user_status
                  FROM users u";
        $db = new Database();
        return $db->getAll($query);
    }

    public static function addUser() {
        $controll=array(0=>false,1=>'error');
        if (isset($_POST['save'])) {
            if ($_SESSION['status'] !== 'admin') {
                die('Juurdepääs keelatud');
            }

            $errorString = "";
            $name = trim($_POST['name']);
            $login = trim($_POST['login']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm'];
            $status = $_POST['status'];

            $allowedStatus = ['admin','moderaator'];
            if (!in_array($status, $allowedStatus)) {
                $errorString .= "Vigane kasutaja staatus.<br>";
            }

            $db = new Database();
            $exist = $db->getOne("SELECT id FROM users WHERE login=?", [$login]);
            if ($exist) {
                $errorString .= "See kasutajatunnus on juba kasutusel.<br>";
            }

            $picture = NULL;
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
                $maxFileSize = 2 * 1024 * 1024;
                $fileTmpPath = $_FILES['picture']['tmp_name'];
                $fileName = $_FILES['picture']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                $allowedExtensions = ['jpg','jpeg','png','jfif'];
                $allowedMime = ['image/jpeg', 'image/png'];

                if ($_FILES['picture']['size'] > $maxFileSize) {
                    $errorString .= "Fail on liiga suur (maksimaalselt 2 MB).<br>";
                } else {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $fileTmpPath);
                    finfo_close($finfo);

                    if (!in_array($mime, $allowedMime) || !in_array($fileExtension, $allowedExtensions)) {
                        $errorString .= "Lubatud vormingud: JPG, JPEG, PNG ja JFIF.<br>";
                    } else {
                        $uploadDir = dirname(__DIR__, 2) . '/public/images/users/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                        $newFileName = bin2hex(random_bytes(16)) . '.' . $fileExtension;
                        $destPath = $uploadDir . $newFileName;

                        if (move_uploaded_file($fileTmpPath, $destPath)) {
                            chmod($destPath, 0644);
                            $picture = 'images/users/' . $newFileName;
                        } else {
                            $errorString .= "Faili üleslaadimine ebaõnnestus.<br>";
                        }
                    }
                }
            }

            if (!$password || !$confirm || mb_strlen($password) < 8) {
                $errorString.="Parool peab olema vähemalt 8 tähemärki pikk.<br>";
            }

            if ($password != $confirm) {
                $errorString.="Paroolid ei kattu.<br>";
            }

            if (mb_strlen($errorString)==0 ) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $date=Date("Y-m-d");

                $sql="INSERT INTO `users` (`username`, `picture`, `login`, `password`, `status`, `registration_date`) VALUES (?, ?, ?, ?, ?, ?)";
                $db = new Database();
                $item = $db->executeRun($sql, [$name, $picture, $login, $passwordHash, $status, $date]);
                    if($item)
                        $controll=array(0=>true);
                    else
                        $controll=array(0=>false,1=>'error');
            }else{
                $controll=array(0=>false,1=>$errorString);
            }
        }
        return $controll;
    }

    public static function getUserDetail($id) {
        $id = (int)$id;
        $query = "SELECT u.id AS user_id,
                        u.username AS user_name,
                        u.login AS user_login,
                        u.picture AS picture,
                        u.status AS user_status
                    FROM users u
                    WHERE u.id = ?";
        $db = new Database();
        return $db->getOne($query, [$id]);
    }
}