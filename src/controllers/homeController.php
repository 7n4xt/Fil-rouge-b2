<?php

class HomeController
{
    public function __construct(PDO $pdo)
    {
    }

    public function index(): void
    {
        header('Location: /properties');
        exit();
    }
}
