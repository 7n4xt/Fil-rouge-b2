<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize database connection
require_once __DIR__ . '/../src/config/database.php';
$pdo = Database::getConnection();

// Include controllers

require_once __DIR__ . '/../src/controllers/propertyListController.php';
require_once __DIR__ . '/../src/controllers/propertyDetailController.php';
require_once __DIR__ . '/../src/controllers/registerController.php';
require_once __DIR__ . '/../src/controllers/homeController.php';
require_once __DIR__ . '/../src/controllers/loginController.php';
require_once __DIR__ . '/../src/controllers/agentDashboardController.php';
require_once __DIR__ . '/../src/controllers/adminDashboardController.php';

// Simple redirect function for PHP Development Server
function redirect($path) {
    header('Location: ' . $path);
    exit();
}

function require_auth(?string $role = null): void {
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo 'Unauthorized. Please login.';
        exit();
    }
    if ($role !== null && ($_SESSION['role'] ?? null) !== $role) {
        http_response_code(403);
        echo 'Forbidden.';
        exit();
    }
}

// Get the request URI and method
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

// Route the request to the appropriate controller based on URI and method
switch ($request_uri) {
    case '/':
        $controller = new HomeController($pdo);
        $controller->index();
        break;
    case '/properties':
        $controller = new PropertyListController($pdo);
        $controller->index();
        break;
    case '/register':
        $controller = new RegisterController($pdo);
        if ($request_method === 'POST') {
            $controller->register();
        } else {
            $controller->showRegistrationForm();
        }
        break;
    case '/login':
        $controller = new LoginController($pdo);
        if ($request_method === 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;
    case '/logout':
        $controller = new LoginController($pdo);
        $controller->logout();
        break;
    case '/admin':
        require_auth('admin');
        $controller = new AdminDashboardController($pdo);
        $controller->index();
        break;
    case '/agent':
        require_auth('agent');
        $controller = new AgentDashboardController($pdo);
        $controller->index();
        break;
    default:
        // Check for property detail route /properties/{id}
        if (preg_match('#^/properties/(\d+)$#', $request_uri, $matches)) {
            $estateId = intval($matches[1]);
            $controller = new PropertyDetailController($pdo);
            $controller->show($estateId);
        } else {
            http_response_code(404);
            echo "Page not found.";
        }
        break;
}