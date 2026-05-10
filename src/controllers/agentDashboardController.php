<?php

class AgentDashboardController
{
    public function __construct(PDO $pdo)
    {
    }

    public function index(): void
    {
        require __DIR__ . '/../views/agentDashboard.php';
    }
}
