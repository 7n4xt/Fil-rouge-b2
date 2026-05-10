<?php
require_once __DIR__ . '/../models/registerModel.php';
class RegisterController {
    private RegisterModel $register_model;
    public function __construct(PDO $pdo) { $this->register_model = new RegisterModel($pdo); }
    public function showRegistrationForm(array $errors = [], array $old = []): void { require __DIR__ . '/../views/register.php'; }
    public function register(): void {
        $old = ['first_name' => trim($_POST['first_name'] ?? ''), 'last_name' => trim($_POST['last_name'] ?? ''), 'mail' => trim($_POST['mail'] ?? ''), 'phone_number' => trim($_POST['phone_number'] ?? '')];
        $password = $_POST['password'] ?? ''; $password_confirm = $_POST['password_confirm'] ?? ''; $errors = [];
        if ($old['first_name'] === '' || $old['last_name'] === '') { $errors[] = 'First and last name are required.'; }
        if (!filter_var($old['mail'], FILTER_VALIDATE_EMAIL)) { $errors[] = 'Please provide a valid email.'; }
        if (strlen($password) < 8) { $errors[] = 'Password must contain at least 8 characters.'; }
        if ($password !== $password_confirm) { $errors[] = 'Password confirmation does not match.'; }
        if ($this->register_model->mail_exists($old['mail'])) { $errors[] = 'This email is already registered.'; }
        if (!empty($errors)) { $this->showRegistrationForm($errors, $old); return; }
        $created = $this->register_model->create_user(['first_name' => $old['first_name'], 'last_name' => $old['last_name'], 'mail' => $old['mail'], 'phone_number' => $old['phone_number'], 'password' => password_hash($password, PASSWORD_DEFAULT)]);
        if (!$created) { $this->showRegistrationForm(['Registration failed, please try again.'], $old); return; }
        $_SESSION['flash_success'] = 'Account created. You can now log in.'; header('Location: /login'); exit();
    }
}
