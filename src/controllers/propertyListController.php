<?php

require_once __DIR__ . '/../models/propertyListModel.php';

class PropertyListController {
    private PDO $pdo;
    private PropertyListModel $model;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->model = new PropertyListModel($pdo);
    }

    /**
     * Display list of properties with pagination and filters
     */
    public function index(): void {
        try {
            // Get current page
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

            // Build filters from GET parameters
            $filters = [
                'type' => $_GET['type'] ?? '',
                'minPrice' => $_GET['minPrice'] ?? '',
                'maxPrice' => $_GET['maxPrice'] ?? '',
                'minRooms' => $_GET['minRooms'] ?? '',
                'country' => $_GET['country'] ?? ''
            ];

            // Remove empty filter values
            $filters = array_filter($filters);

            // Get properties
            $data = $this->model->getProperties($page, $filters);

            // Get filter options
            $data['types'] = $this->model->getPropertyTypes();
            $data['countries'] = $this->model->getCountries();
            $data['priceRange'] = $this->model->getPriceRange();
            $data['currentFilters'] = $filters;

            // Load view
            require __DIR__ . '/../views/propertyList.php';
        } catch (Exception $e) {
            error_log("Error in PropertyListController::index: " . $e->getMessage());
            $this->showErrorPage();
        }
    }

    /**
     * Show error fallback page
     */
    private function showErrorPage(): void {
        http_response_code(500);
        require __DIR__ . '/../views/error.php';
    }
}
