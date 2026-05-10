<?php

declare(strict_types=1);

class LeadRequestModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $userId, int $estateId, string $kind, string $message, ?string $preferredVisitAt): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO lead_request (user_id, estate_id, request_kind, message, preferred_visit_at, status)
             VALUES (?, ?, ?, ?, ?, \'nouveau\')'
        );

        return $stmt->execute([
            $userId,
            $estateId,
            $kind,
            $message !== '' ? $message : null,
            $preferredVisitAt !== null && $preferredVisitAt !== '' ? $preferredVisitAt : null,
        ]);
    }

    /** @return list<array<string, mixed>> */
    public function listForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT lr.*, e.estate_address, e.estate_type
             FROM lead_request lr
             INNER JOIN estate e ON e.estate_id = lr.estate_id
             WHERE lr.user_id = ?
             ORDER BY lr.created_at DESC'
        );
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /** @return list<array<string, mixed>> */
    public function listForAgent(int $agentId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT lr.*, e.estate_address, u.first_name AS client_first_name, u.last_name AS client_last_name, u.mail AS client_mail
             FROM lead_request lr
             INNER JOIN estate e ON e.estate_id = lr.estate_id
             INNER JOIN user_ u ON u.user_id = lr.user_id
             WHERE e.agent_id = ?
             ORDER BY lr.created_at DESC'
        );
        $stmt->execute([$agentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function updateByAgent(int $leadRequestId, int $agentId, string $status, ?string $agentNote): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE lead_request lr
             INNER JOIN estate e ON e.estate_id = lr.estate_id
             SET lr.status = ?, lr.agent_note = ?
             WHERE lr.lead_request_id = ? AND e.agent_id = ?'
        );

        return $stmt->execute([$status, $agentNote !== '' ? $agentNote : null, $leadRequestId, $agentId]);
    }
}
