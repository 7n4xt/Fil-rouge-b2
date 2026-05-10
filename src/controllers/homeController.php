<?php

class HomeController
{
    public function __construct(PDO $pdo)
    {
    }

    public function index(): void
    {
        $first_name = $_SESSION['first_name'] ?? null;
        require __DIR__ . '/../views/home.php';
    }
}
