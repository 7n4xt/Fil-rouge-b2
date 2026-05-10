<?php

declare(strict_types=1);

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../src/helpers/csrf.php';

require_once __DIR__ . '/../src/config/database.php';

require_once __DIR__ . '/../src/controllers/propertyListController.php';
require_once __DIR__ . '/../src/controllers/propertyDetailController.php';
require_once __DIR__ . '/../src/controllers/registerController.php';
require_once __DIR__ . '/../src/controllers/homeController.php';
require_once __DIR__ . '/../src/controllers/loginController.php';
require_once __DIR__ . '/../src/controllers/agentDashboardController.php';
require_once __DIR__ . '/../src/controllers/adminDashboardController.php';
require_once __DIR__ . '/../src/controllers/PropertyLeadController.php';
require_once __DIR__ . '/../src/controllers/ClientAccountController.php';
require_once __DIR__ . '/../src/controllers/AgentDemandeController.php';
require_once __DIR__ . '/../src/controllers/AgentDossierController.php';
require_once __DIR__ . '/../src/controllers/AgentEstateController.php';
require_once __DIR__ . '/../src/controllers/AdminAnalyticsController.php';

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
    $msg = $e->getMessage();
    $hint = 'Vérifiez que MySQL/MariaDB est démarré, que la base existe, et que les identifiants dans .env sont corrects.';
    if (str_contains($msg, 'Missing') || str_contains($msg, 'environment variable')) {
        $hint = 'Créez un fichier .env à la racine du projet (copiez .env.example) et renseignez DB_HOST, DB_NAME, DB_USERNAME et DB_PASSWORD.';
    } elseif (str_contains($msg, '2002') || str_contains($msg, 'refused') || str_contains($msg, 'actively refused')) {
        $hint = 'MySQL ne répond pas sur ce serveur/port : démarrez le service MySQL (XAMPP/WAMP/MariaDB) ou corrigez DB_HOST.';
    } elseif (str_contains($msg, '1049') || str_contains($msg, 'Unknown database')) {
        $hint = 'La base indiquée dans DB_NAME n’existe pas : créez-la ou importez real_estate.sql.';
    } elseif (str_contains($msg, '1045') || str_contains($msg, 'Access denied')) {
        $hint = 'Identifiants refusés : vérifiez DB_USERNAME et DB_PASSWORD dans .env.';
    }
    render_http_error(503, 'Base de données inaccessible', $hint . ' (détail technique : ' . $msg . ')');
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
        case '/account':
            require_auth('user');
            (new ClientAccountController($pdo))->index();
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
        case '/admin/analytics':
            require_auth('admin');
            (new AdminAnalyticsController($pdo))->index();
            break;
        case '/agent':
            require_auth('agent');
            (new AgentDashboardController($pdo))->index();
            break;
        case '/agent/demandes':
            require_auth('agent');
            $c = new AgentDemandeController($pdo);
            $request_method === 'POST' ? $c->update() : $c->index();
            break;
        case '/agent/dossiers':
            require_auth('agent');
            $c = new AgentDossierController($pdo);
            if ($request_method === 'POST') {
                if (isset($_POST['create_dossier'])) {
                    $c->create();
                } else {
                    $c->update();
                }
            } else {
                $c->index();
            }
            break;
        case '/agent/biens':
            require_auth('agent');
            (new AgentEstateController($pdo))->index();
            break;
        case '/agent/biens/nouveau':
            require_auth('agent');
            $c = new AgentEstateController($pdo);
            $request_method === 'POST' ? $c->create() : $c->newForm();
            break;
        default:
            if (preg_match('#^/properties/(\d+)$#', $request_uri, $matches)) {
                $pid = (int) $matches[1];
                if ($request_method === 'POST') {
                    (new PropertyLeadController($pdo))->submit($pid);
                } else {
                    (new PropertyDetailController($pdo))->show($pid);
                }
            } elseif (preg_match('#^/agent/biens/editer/(\d+)$#', $request_uri, $matches)) {
                require_auth('agent');
                $eid = (int) $matches[1];
                $c = new AgentEstateController($pdo);
                $request_method === 'POST' ? $c->update($eid) : $c->editForm($eid);
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
