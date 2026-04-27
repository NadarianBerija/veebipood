<?php
class Login {
    public static function authentication() {
        if (isset($_SESSION['sessionId'])) {
            return true;
        }

        if (isset($_POST['btnLogin'])) {
            $login = trim($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($login !== '' && $password !== '') {
                $db = new Database();
                $sql = "SELECT * FROM users WHERE login = :login LIMIT 1";
                $item = $db->getOne($sql, ['login' => $login]);

                if ($item && password_verify($password, $item['password'])) {
                    $_SESSION['sessionId'] = session_id();
                    $_SESSION['userId'] = $item['id'];
                    $_SESSION['name'] = $item['username'];
                    $_SESSION['status'] = $item['status'];
                    return true;
                }
            }
        }
        return false;
    }

    public static function logout() {
        unset($_SESSION['sessionId']);
        unset($_SESSION['userId']);
        unset($_SESSION['name']);
        unset($_SESSION['status']);
        session_destroy();
    }
}