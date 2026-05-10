<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../src/config/database.php';

require_once __DIR__ . '/../src/controllers/propertyListController.php';
require_once __DIR__ . '/../src/controllers/propertyDetailController.php';
require_once __DIR__ . '/../src/controllers/registerController.php';
require_once __DIR__ . '/../src/controllers/homeController.php';
require_once __DIR__ . '/../src/controllers/loginController.php';
require_once __DIR__ . '/../src/controllers/agentDashboardController.php';
require_once __DIR__ . '/../src/controllers/adminDashboardController.php';

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit();
}

function render_http_error(int $code, string $title, string $message): void
{
    http_response_code($code);
    $http_error_code = $code;
    $http_error_title = $title;
    $http_error_message = $message;
    require __DIR__ . '/../src/views/http_error.php';
    exit();
}

function require_auth(?string $role = null): void
{
    if (empty($_SESSION['user_id'])) {
        render_http_error(401, 'Connexion requise', 'Identifiez-vous pour accéder à cette page.');
    }
    if ($role !== null && ($_SESSION['role'] ?? null) !== $role) {
        render_http_error(403, 'Accès refusé', 'Votre rôle ne permet pas d’ouvrir cette section.');
    }
}

try {
    $pdo = Database::getConnection();
} catch (Throwable $e) {
    error_log('Application bootstrap (database): ' . $e->getMessage());
    render_http_error(503, 'Service indisponible', 'La base de données est momentanément inaccessible. Réessayez plus tard.');
}

$request_uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    switch ($request_uri) {
        case '/':
            (new HomeController($pdo))->index();
            break;
        case '/properties':
            (new PropertyListController($pdo))->index();
            break;
        case '/register':
            $c = new RegisterController($pdo);
            $request_method === 'POST' ? $c->register() : $c->showRegistrationForm();
            break;
        case '/login':
            $c = new LoginController($pdo);
            $request_method === 'POST' ? $c->login() : $c->showLoginForm();
            break;
        case '/logout':
            (new LoginController($pdo))->logout();
            break;
        case '/admin':
            require_auth('admin');
            (new AdminDashboardController($pdo))->index();
            break;
        case '/agent':
            require_auth('agent');
            (new AgentDashboardController($pdo))->index();
            break;
        default:
            if (preg_match('#^/properties/(\d+)$#', $request_uri, $matches)) {
                (new PropertyDetailController($pdo))->show((int) $matches[1]);
            } else {
                render_http_error(404, 'Page introuvable', 'L’URL demandée ne correspond à aucune ressource du site.');
            }
            break;
    }
} catch (Throwable $e) {
    error_log('Unhandled route error: ' . $e->getMessage());
    http_response_code(500);
    require __DIR__ . '/../src/views/error.php';
}
