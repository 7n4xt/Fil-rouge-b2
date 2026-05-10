<?php

require_once __DIR__ . '/../models/propertyDetailModel.php';

class PropertyDetailController {
    private PDO $pdo;
    private PropertyDetailModel $model;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->model = new PropertyDetailModel($pdo);
    }

    /**
     * Display property detail page
     */
    public function show(int $estateId): void {
        try {
            // Get property details
            $data = $this->model->getPropertyById($estateId);

            if (!$data) {
                http_response_code(404);
                $this->showNotFoundPage();
                return;
            }

            // Get similar properties
            $data['similar'] = $this->model->getSimilarProperties($estateId);

            $data['flash_success'] = $_SESSION['flash_success'] ?? null;
            $data['flash_error'] = $_SESSION['flash_error'] ?? null;
            unset($_SESSION['flash_success'], $_SESSION['flash_error']);
            $data['is_client'] = ($_SESSION['role'] ?? null) === 'user';

            // Load view
            require __DIR__ . '/../views/propertyDetail.php';
        } catch (Exception $e) {
            error_log("Error in PropertyDetailController::show: " . $e->getMessage());
            $this->showErrorPage();
        }
    }

    /**
     * Show 404 page
     */
    private function showNotFoundPage(): void {
        require __DIR__ . '/../views/notFound.php';
    }

    /**
     * Show error fallback page
     */
    private function showErrorPage(): void {
        http_response_code(500);
        require __DIR__ . '/../views/error.php';
    }
}
