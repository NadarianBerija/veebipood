<?php
class Login {
    public static function authentication() {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }

        if (!isset($_SESSION['last_attempt_time'])) {
            $_SESSION['last_attempt_time'] = 0;
        }

        if ($_SESSION['login_attempts'] > 5 && time() - $_SESSION['last_attempt_time'] >= 600) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = 0;
        }

        if ($_SESSION['login_attempts'] > 5 && time() - $_SESSION['last_attempt_time'] < 600) {
            $_SESSION['errorString'] = 'Olete ületanud maksimaalse sisselogimiskatsete arvu. Proovige hiljem uuesti.';
            return false;
        }

        if (isset($_SESSION['sessionId'])) {
            return true;
        }

        if (!isset($_POST['btnLogin'])) {
            return false;
        }
        
        if (
            !isset($_POST['csrf_token']) || 
            !isset($_SESSION['csrf_token']) || 
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            $_SESSION['errorString'] = 'Vigane CSRF token';
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            return false;
        }
        
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if ($login === '' || $password === '') {
            $_SESSION['errorString'] = 'Sisesta kasutajanimi ja parool';
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            return false;
        }
            
        $db = new Database();
        $sql = "SELECT * FROM users WHERE login = ? LIMIT 1";
        $item = $db->getOne($sql, [$login]);

        if (!$item) {
            $_SESSION['errorString'] = 'Vale kasutajanimi või parool';
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();

            return false;
        }

        if ((int)$item['is_deleted'] === 1) {

            $_SESSION['errorString'] = 'Kasutaja on blokeeritud';
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();

            return false;
        }
        
        
        if ($item && password_verify($password, $item['password'])) {
            session_regenerate_id(true);
            $_SESSION['sessionId'] = session_id();
            $_SESSION['userId'] = $item['id'];
            $_SESSION['name'] = $item['username'];
            $_SESSION['status'] = $item['status'];

            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = 0;
            unset($_SESSION['errorString']);

            return true;
                
            
        }

        $_SESSION['errorString'] = 'Vale kasutajanimi või parool';
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = time();

        return false;
    }

    public static function logout() {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }
}