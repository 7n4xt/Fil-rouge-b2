<?php

declare(strict_types=1);

class ClientDossierModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** Étapes affichées côté client et agent */
    public const STEPS = ['contact', 'visite', 'offre', 'compromis', 'acte', 'termine'];

    /** @return list<array<string, mixed>> */
    public function listForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT d.dossier_id, d.flow_type, d.step, d.title, d.notes_public, d.updated_at,
                    e.estate_address, u.first_name AS agent_first_name, u.last_name AS agent_last_name
             FROM client_dossier d
             INNER JOIN user_ u ON u.user_id = d.agent_id
             LEFT JOIN estate e ON e.estate_id = d.estate_id
             WHERE d.user_id = ?
             ORDER BY d.updated_at DESC'
        );
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /** @return list<array<string, mixed>> */
    public function listForAgent(int $agentId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT d.*, e.estate_address,
                    u.first_name AS client_first_name, u.last_name AS client_last_name, u.mail AS client_mail
             FROM client_dossier d
             INNER JOIN user_ u ON u.user_id = d.user_id
             LEFT JOIN estate e ON e.estate_id = d.estate_id
             WHERE d.agent_id = ?
             ORDER BY d.updated_at DESC'
        );
        $stmt->execute([$agentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function create(int $userId, int $agentId, ?int $estateId, string $flowType, string $title, ?string $notesPublic): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO client_dossier (user_id, agent_id, estate_id, flow_type, step, title, notes_public)
             VALUES (?, ?, ?, ?, \'contact\', ?, ?)'
        );

        return $stmt->execute([
            $userId,
            $agentId,
            $estateId,
            $flowType,
            $title !== '' ? $title : null,
            $notesPublic !== '' ? $notesPublic : null,
        ]);
    }

    public function updateByAgent(int $dossierId, int $agentId, string $step, ?string $notesPublic, ?string $notesInternal): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE client_dossier SET step = ?, notes_public = ?, notes_internal = ?
             WHERE dossier_id = ? AND agent_id = ?'
        );

        return $stmt->execute([
            $step,
            $notesPublic !== '' ? $notesPublic : null,
            $notesInternal !== '' ? $notesInternal : null,
            $dossierId,
            $agentId,
        ]);
    }

    public function findClientIdByMail(string $mail): ?int
    {
        $stmt = $this->pdo->prepare(
            'SELECT user_id FROM user_ WHERE mail = ? AND is_admin = 0 AND is_agent = 0 LIMIT 1'
        );
        $stmt->execute([$mail]);
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }
}
