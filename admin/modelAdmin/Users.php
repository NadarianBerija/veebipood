<?php
class Users {
    private static function checkCSRF() {
        if (!isset($_POST['csrf_token']) ||
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRF validation failed');
        }
    }

    private static function clean($value) {
        return trim($value);
    }

    public static function getAllUsers() {
        $query = "SELECT u.id AS user_id,
                         u.username AS user_name,
                         u.picture AS picture,
                         u.status AS user_status,
                         u.is_deleted
                  FROM users u";
        $db = new Database();
        return $db->getAll($query);
    }

    public static function addUser() {
        self::checkCSRF();

        $controll=array(0=>false,1=>'error');
        if (isset($_POST['save'])) {
            if ($_SESSION['status'] !== 'admin') {
                die('Juurdepääs keelatud');
            }

            $errorString = "";
            $name = self::clean($_POST['name'] ?? '');
            $login = self::clean($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? '';
            $status = $_POST['status'] ?? '';

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
                $allowedMime = ['image/jpeg', 'image/png', 'image/jfif'];

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

    /**
     * Retrieves detailed information for a specific user.
     * @param int $id The user's ID.
     * @return array|false User data or false if not found.
     */
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

    /**
     * Handles the update of an existing user's information.
     * @param int $id The ID of the user to edit.
     * @return array Result of the operation.
     */
    public static function editUser($id) {
        self::checkCSRF();

        $id = (int)$id;
        $result = [0 => false, 1 => "error"];

        if (isset($_POST['save'])) {

            if ($_SESSION['status'] !== 'admin') {
                die('Juurdepääs keelatud');
            }

            $errorString = "";

            $name = self::clean($_POST['name'] ?? '');
            $login = self::clean($_POST['login'] ?? '');
            $status = $_POST['status'] ?? '';

            $changePassword = isset($_POST['changePassword']);

            $allowedStatus = ['admin','moderaator'];
            if (!in_array($status, $allowedStatus)) {
                $errorString .= "Vigane kasutaja staatus.<br>";
            }

            $db = new Database();

            $exist = $db->getOne(
                "SELECT id FROM users WHERE login = ? AND id != ?", 
                [$login, $id]
            );
            if ($exist) {
                $errorString .= "See kasutajatunnus on juba kasutusel.<br>";
            }

            $passwordHash = null;

            if ($changePassword) {
                $oldPassword = $_POST['oldPassword'] ?? '';
                $newPassword = $_POST['newPassword'] ?? '';
                $confirmPassword = $_POST['confirmPassword'] ?? '';

                if (!$newPassword || !$confirmPassword || mb_strlen($newPassword) < 8) {
                    $errorString .= "Parool peab olema vähemalt 8 tähemärki pikk.<br>";
                }

                if ($newPassword !== $confirmPassword) {
                    $errorString .= "Paroolid ei kattu.<br>";
                }

                $item = $db->getOne(
                    "SELECT password FROM users WHERE id = ?", 
                    [$id]
                );

                if (!$item || !password_verify($oldPassword, $item['password'])) {
                    $errorString .= "Vale praegune parool.<br>";
                }

                if (!$errorString) {
                    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                }
            }

            $picture = NULL;

            if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
                $maxFileSize = 2 * 1024 * 1024;
                $fileTmpPath = $_FILES['picture']['tmp_name'];
                $fileName = $_FILES['picture']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                $allowedExtensions = ['jpg','jpeg','png','jfif'];
                $allowedMime = ['image/jpeg','image/png', 'image/jfif'];

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

            if (!$errorString) {

                $fields = [
                    "username = ?",
                    "login = ?",
                    "status = ?"
                ];
                $params = [$name, $login, $status];

                if ($picture !== NULL) {
                    $fields[] = "picture = ?";
                    $params[] = $picture;
                }

                if ($passwordHash !== null) {
                    $fields[] = "password = ?";
                    $params[] = $passwordHash;
                }

                $params[] = $id;

                $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
                $item = $db->executeRun($sql, $params);

                if ($item) {
                    $result = [0 => true];
                } else {
                    $result = [0 => false, 1 => "Andmebaasi viga"];
                }

            } else {
                $result = [0 => false, 1 => $errorString];
            }
        }

        return $result;
    }

    /**
     * Retrieves the deletion status of a user.
     * @param int $id The user's ID.
     * @return int Deletion status (1 if deleted, 0 otherwise).
     */
    public static function getDeletedStatus($id) {
        $db = new Database();

        $query = "SELECT u.is_deleted FROM users u WHERE id = ?";

        return $db->getOne($query, [$id])['is_deleted'];
    }

    /**
     * Toggles the deletion status of a user and their associated arts.
     * @param int $id The user's ID.
     * @return int The new deletion status.
     */
    public static function toggleDeleted($id) {
        $db = new Database();

        $current = self::getDeletedStatus($id);
        $new = $current ? 0 : 1;

        $queryUser = "UPDATE users SET is_deleted = ? WHERE id = ?";
        $db->executeRun($queryUser, [$new, $id]);

        $queryArts = "UPDATE arts SET is_deleted = ? WHERE user_id = ?";
        $db->executeRun($queryArts, [$new, $id]);

        return $new;
    }
}