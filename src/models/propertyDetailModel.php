<?php

class PropertyDetailModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Get complete property details by ID
     */
    public function getPropertyById(int $estateId): ?array {
        try {
            $query = "
                SELECT 
                    e.estate_id,
                    e.estate_type,
                    e.estate_address,
                    e.estate_country,
                    e.price,
                    e.surface,
                    e.rooms_count,
                    e.bedrooms_count,
                    e.description,
                    e.estate_status,
                    e.energy_class,
                    a.agence_name,
                    a.agence_address,
                    u.first_name,
                    u.last_name,
                    u.phone_number,
                    u.mail
                FROM estate e
                LEFT JOIN agence a ON e.agence_id = a.agence_id
                LEFT JOIN user_ u ON e.agent_id = u.user_id
                WHERE e.estate_id = ?
            ";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$estateId]);
            $property = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$property) {
                return null;
            }

            // Get all photos
            $property['photos'] = $this->getPhotos($estateId);
            
            // Get all files/documents
            $property['files'] = $this->getFiles($estateId);

            return $property;
        } catch (PDOException $e) {
            error_log("Database error in getPropertyById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all photos for a property
     */
    private function getPhotos(int $estateId): array {
        try {
            $query = "SELECT photo_id, url_path FROM photo WHERE estate_id = ? ORDER BY photo_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$estateId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Database error in getPhotos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all files/documents for a property
     */
    private function getFiles(int $estateId): array {
        try {
            $query = "SELECT file_id, file_name, file_url, file_type FROM estatefile WHERE estate_id = ? ORDER BY file_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$estateId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Database error in getFiles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if property exists and is available
     */
    public function propertyExists(int $estateId): bool {
        try {
            $query = "SELECT COUNT(*) FROM estate WHERE estate_id = ? AND estate_status = 'available'";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$estateId]);
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database error in propertyExists: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get similar properties (same type, nearby price)
     */
    public function getSimilarProperties(int $estateId, int $limit = 4): array {
        try {
            $property = $this->getPropertyById($estateId);
            if (!$property) {
                return [];
            }

            $priceRange = $property['price'] * 0.2; // 20% range

            $query = "
                SELECT 
                    e.estate_id,
                    e.estate_type,
                    e.estate_address,
                    e.price,
                    e.surface,
                    e.rooms_count,
                    e.bedrooms_count,
                    COALESCE(p.url_path, 'pictures/house/default.jpg') as main_photo
                FROM estate e
                LEFT JOIN photo p ON e.estate_id = p.estate_id
                WHERE e.estate_type = ?
                  AND e.estate_status = 'available'
                  AND e.estate_id != ?
                  AND e.price BETWEEN ? AND ?
                GROUP BY e.estate_id
                ORDER BY RAND()
                LIMIT ?
            ";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                $property['estate_type'],
                $estateId,
                $property['price'] - $priceRange,
                $property['price'] + $priceRange,
                $limit
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Database error in getSimilarProperties: " . $e->getMessage());
            return [];
        }
    }
}
