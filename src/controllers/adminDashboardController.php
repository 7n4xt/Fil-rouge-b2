<?php

require_once __DIR__ . '/../models/adminDashboardModel.php';

class AdminDashboardController
{
    private AdminDashboardModel $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new AdminDashboardModel($pdo);
    }

    public function index(): void
    {
        try {
            $data = [
                'summary' => $this->model->get_summary(),
                'status_breakdown' => $this->model->get_estates_by_status(),
                'recent_transactions' => $this->model->get_recent_transactions(),
                'top_agencies' => $this->model->get_top_agencies(),
            ];
            require __DIR__ . '/../views/adminDashboard.php';
        } catch (Throwable $e) {
            error_log('AdminDashboardController: ' . $e->getMessage());
            http_response_code(500);
            require __DIR__ . '/../views/error.php';
        }
    }
}
