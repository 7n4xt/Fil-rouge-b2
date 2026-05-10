<?php
require_once __DIR__ . '/../models/loginModel.php';
class LoginController {
    private LoginModel $login_model;
    public function __construct(PDO $pdo) { $this->login_model = new LoginModel($pdo); }
    public function showLoginForm(array $errors = []): void {
        $flash_success = $_SESSION['flash_success'] ?? null; unset($_SESSION['flash_success']); require __DIR__ . '/../views/login.php';
    }
    public function login(): void {
        $mail = trim($_POST['mail'] ?? ''); $password = $_POST['password'] ?? ''; $errors = [];
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Please provide a valid email.'; }
        if ($password === '') { $errors[] = 'Password is required.'; }
        if (!empty($errors)) { $this->showLoginForm($errors); return; }
        $user = $this->login_model->get_user_by_mail($mail);
        if (!$user || !password_verify($password, $user['password'])) { $this->showLoginForm(['Invalid email or password.']); return; }
        session_regenerate_id(true); $_SESSION['user_id'] = (int) $user['user_id']; $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['role'] = !empty($user['is_admin']) ? 'admin' : (!empty($user['is_agent']) ? 'agent' : 'user');
        $redirect_path = $_SESSION['role'] === 'admin' ? '/admin' : ($_SESSION['role'] === 'agent' ? '/agent' : '/');
        header('Location: ' . $redirect_path); exit();
    }

    public function logout(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        header('Location: /login');
        exit();
    }
}
