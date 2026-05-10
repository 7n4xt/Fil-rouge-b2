<?php

require_once __DIR__ . '/../models/agentDashboardModel.php';

class AgentDashboardController
{
    private AgentDashboardModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new AgentDashboardModel($pdo);
    }

    public function index(): void
    {
        $user_id = (int) ($_SESSION['user_id'] ?? 0);
        try {
            $data = [
                'summary' => $this->model->get_summary_for_agent($user_id),
                'listings' => $this->model->get_recent_listings($user_id),
                'sales' => $this->model->get_recent_sales_for_agent($user_id),
            ];
            require __DIR__ . '/../views/agentDashboard.php';
        } catch (Throwable $e) {
            error_log('AgentDashboardController: ' . $e->getMessage());
            http_response_code(500);
            require __DIR__ . '/../views/error.php';
        }
    }
}
