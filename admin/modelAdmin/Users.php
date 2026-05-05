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
                die('Access denied');
            }

            $errorString = "";
            $name = trim($_POST['name']);
            $login = trim($_POST['login']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm'];
            $status = $_POST['status'];

            $allowedStatus = ['admin','moderaator'];
            if (!in_array($status, $allowedStatus)) {
                $errorString .= "Invalid user status.<br>";
            }

            $db = new Database();
            $exist = $db->getOne("SELECT id FROM users WHERE login=?", [$login]);
            if ($exist) {
                $errorString .= "This login is already in use.<br>";
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
                    $errorString .= "Maximum picture size is 2MB.<br>";
                } else {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $fileTmpPath);
                    finfo_close($finfo);

                    if (!in_array($mime, $allowedMime) || !in_array($fileExtension, $allowedExtensions)) {
                        $errorString .= "Allowed formats: JPG, JPEG, PNG, and JFIF.<br>";
                    } else {
                        $uploadDir = dirname(__DIR__, 2) . '/public/images/users/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                        $newFileName = bin2hex(random_bytes(16)) . '.' . $fileExtension;
                        $destPath = $uploadDir . $newFileName;

                        if (move_uploaded_file($fileTmpPath, $destPath)) {
                            chmod($destPath, 0644);
                            $picture = 'images/users/' . $newFileName;
                        } else {
                            $errorString .= "File upload failed.<br>";
                        }
                    }
                }
            }

            if (!$password || !$confirm || mb_strlen($password) < 8) {
                $errorString.="Password must be at least 8 characters long.<br>";
            }

            if ($password != $confirm) {
                $errorString.="Passwords do not match.<br>";
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
}