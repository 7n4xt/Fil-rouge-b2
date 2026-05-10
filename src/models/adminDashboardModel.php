<?php

class AdminDashboardModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get_summary(): array
    {
        return [
            'user_count' => (int) $this->pdo->query('SELECT COUNT(*) FROM user_')->fetchColumn(),
            'agent_count' => (int) $this->pdo->query('SELECT COUNT(*) FROM user_ WHERE is_agent = 1')->fetchColumn(),
            'admin_count' => (int) $this->pdo->query('SELECT COUNT(*) FROM user_ WHERE is_admin = 1')->fetchColumn(),
            'estate_count' => (int) $this->pdo->query('SELECT COUNT(*) FROM estate')->fetchColumn(),
            'agence_count' => (int) $this->pdo->query('SELECT COUNT(*) FROM agence')->fetchColumn(),
            'transaction_count' => (int) $this->pdo->query('SELECT COUNT(*) FROM transaction')->fetchColumn(),
            'transaction_volume' => (float) $this->pdo->query('SELECT COALESCE(SUM(price_final), 0) FROM transaction')->fetchColumn(),
            'avg_estate_price' => (float) $this->pdo->query('SELECT COALESCE(AVG(price), 0) FROM estate')->fetchColumn(),
        ];
    }

    public function get_estates_by_status(): array
    {
        $stmt = $this->pdo->query(
            'SELECT estate_status, COUNT(*) AS cnt FROM estate GROUP BY estate_status ORDER BY cnt DESC'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function get_recent_transactions(int $limit = 8): array
    {
        $lim = max(1, min(50, $limit));
        $stmt = $this->pdo->prepare(
            'SELECT t.transaction_id, t.transaction_type, t.price_final, t.transaction_date, e.estate_address
             FROM transaction t
             INNER JOIN estate e ON e.estate_id = t.estate_id
             ORDER BY t.transaction_date DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $lim, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function get_top_agencies(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.agence_id, a.agence_name, COUNT(e.estate_id) AS estate_count
             FROM agence a
             LEFT JOIN estate e ON e.agence_id = a.agence_id
             GROUP BY a.agence_id, a.agence_name
             ORDER BY estate_count DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, max(1, min(20, $limit)), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
