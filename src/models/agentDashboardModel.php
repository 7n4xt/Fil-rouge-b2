<?php

class AgentDashboardModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get_summary_for_agent(int $user_id): array
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM estate WHERE agent_id = ?');
        $stmt->execute([$user_id]);
        $listing_count = (int) $stmt->fetchColumn();

        $stmt = $this->pdo->prepare('SELECT COALESCE(AVG(price), 0) FROM estate WHERE agent_id = ?');
        $stmt->execute([$user_id]);
        $avg_price = (float) $stmt->fetchColumn();

        $stmt = $this->pdo->prepare('SELECT COALESCE(SUM(price), 0) FROM estate WHERE agent_id = ?');
        $stmt->execute([$user_id]);
        $portfolio_value = (float) $stmt->fetchColumn();

        return [
            'listing_count' => $listing_count,
            'avg_price' => $avg_price,
            'portfolio_value' => $portfolio_value,
        ];
    }

    public function get_recent_listings(int $user_id, int $limit = 10): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT estate_id, estate_address, estate_type, price, estate_status
             FROM estate WHERE agent_id = ?
             ORDER BY estate_id DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->bindValue(2, max(1, min(50, $limit)), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function get_recent_sales_for_agent(int $user_id, int $limit = 8): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT t.transaction_id, t.transaction_type, t.price_final, t.transaction_date, e.estate_address
             FROM transaction t
             INNER JOIN estate e ON e.estate_id = t.estate_id
             WHERE e.agent_id = ?
             ORDER BY t.transaction_date DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->bindValue(2, max(1, min(50, $limit)), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
