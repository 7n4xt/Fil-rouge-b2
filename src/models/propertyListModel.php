<?php

class PropertyListModel {
    private PDO $pdo;
    private const ITEMS_PER_PAGE = 12;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Get properties with pagination and filters
     * @param int $page Current page number
     * @param array $filters Array with 'type', 'minPrice', 'maxPrice', 'minRooms', 'country'
     * @return array ['properties' => array, 'total' => int, 'pages' => int, 'currentPage' => int]
     */
    public function getProperties(int $page = 1, array $filters = []): array {
        try {
            $offset = ($page - 1) * self::ITEMS_PER_PAGE;

            // Build base query
            $where = ["e.estate_status = 'available'"];
            $params = [];

            // Add filters
            if (!empty($filters['type'])) {
                $where[] = "e.estate_type = ?";
                $params[] = $filters['type'];
            }
            if (!empty($filters['minPrice'])) {
                $where[] = "e.price >= ?";
                $params[] = floatval($filters['minPrice']);
            }
            if (!empty($filters['maxPrice'])) {
                $where[] = "e.price <= ?";
                $params[] = floatval($filters['maxPrice']);
            }
            if (!empty($filters['minRooms'])) {
                $where[] = "e.rooms_count >= ?";
                $params[] = intval($filters['minRooms']);
            }
            if (!empty($filters['country'])) {
                $where[] = "e.estate_country = ?";
                $params[] = $filters['country'];
            }

            $whereClause = implode(" AND ", $where);

            // Get total count
            $countQuery = "SELECT COUNT(*) as total FROM estate e WHERE " . $whereClause;
            $countStmt = $this->pdo->prepare($countQuery);
            $countStmt->execute($params);
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            $pages = ceil($total / self::ITEMS_PER_PAGE);

            // Get properties with main photo
            $query = "
                SELECT 
                    e.estate_id,
                    e.estate_type,
                    e.estate_address,
                    e.price,
                    e.surface,
                    e.rooms_count,
                    e.bedrooms_count,
                    e.energy_class,
                    e.estate_country,
                    COALESCE(p.url_path, 'pictures/house/default.jpg') as main_photo
                FROM estate e
                LEFT JOIN photo p ON e.estate_id = p.estate_id
                WHERE " . $whereClause . "
                GROUP BY e.estate_id
                ORDER BY e.estate_id DESC
                LIMIT " . self::ITEMS_PER_PAGE . " OFFSET " . $offset . "
            ";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'properties' => $properties,
                'total' => $total,
                'pages' => $pages,
                'currentPage' => $page,
                'itemsPerPage' => self::ITEMS_PER_PAGE
            ];
        } catch (PDOException $e) {
            error_log("Database error in getProperties: " . $e->getMessage());
            return [
                'properties' => [],
                'total' => 0,
                'pages' => 0,
                'currentPage' => 1,
                'error' => 'Unable to fetch properties'
            ];
        }
    }

    /**
     * Get available property types for filter
     */
    public function getPropertyTypes(): array {
        try {
            $query = "SELECT DISTINCT estate_type FROM estate WHERE estate_type IS NOT NULL ORDER BY estate_type";
            $stmt = $this->pdo->query($query);
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $results ?: [];
        } catch (PDOException $e) {
            error_log("Database error in getPropertyTypes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get available countries for filter
     */
    public function getCountries(): array {
        try {
            $query = "SELECT DISTINCT estate_country FROM estate WHERE estate_country IS NOT NULL ORDER BY estate_country";
            $stmt = $this->pdo->query($query);
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $results ?: [];
        } catch (PDOException $e) {
            error_log("Database error in getCountries: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get price range for filter
     */
    public function getPriceRange(): array {
        try {
            $query = "SELECT MIN(price) as minPrice, MAX(price) as maxPrice FROM estate WHERE estate_status = 'available'";
            $stmt = $this->pdo->query($query);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['minPrice' => 0, 'maxPrice' => 1000000];
        } catch (PDOException $e) {
            error_log("Database error in getPriceRange: " . $e->getMessage());
            return ['minPrice' => 0, 'maxPrice' => 1000000];
        }
    }
}
