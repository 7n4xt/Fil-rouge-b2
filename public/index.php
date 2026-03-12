<?php

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
    default:
        http_response_code(404);
        echo "Page not found.";
        break;
}