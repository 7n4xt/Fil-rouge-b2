<?php

declare(strict_types=1);

class AgentEstateModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** @return list<array<string, mixed>> */
    public function listByAgent(int $agentId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT estate_id, estate_address, estate_type, price, estate_status, surface
             FROM estate WHERE agent_id = ? ORDER BY estate_id DESC'
        );
        $stmt->execute([$agentId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /** @return array<string, mixed>|null */
    public function findForAgent(int $estateId, int $agentId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM estate WHERE estate_id = ? AND agent_id = ? LIMIT 1'
        );
        $stmt->execute([$estateId, $agentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /** @return list<array{agence_id:int,agence_name:?string}> */
    public function listAgences(): array
    {
        $stmt = $this->pdo->query('SELECT agence_id, agence_name FROM agence ORDER BY agence_name');

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function insert(int $agentId, array $row): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO estate (
                estate_country, estate_address, estate_type, price, surface,
                rooms_count, bedrooms_count, description, estate_status, energy_class,
                agent_id, agence_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $row['estate_country'],
            $row['estate_address'],
            $row['estate_type'],
            $row['price'],
            $row['surface'],
            $row['rooms_count'],
            $row['bedrooms_count'],
            $row['description'],
            $row['estate_status'],
            $row['energy_class'],
            $agentId,
            $row['agence_id'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $estateId, int $agentId, array $row): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE estate SET
                estate_country = ?, estate_address = ?, estate_type = ?, price = ?, surface = ?,
                rooms_count = ?, bedrooms_count = ?, description = ?, estate_status = ?, energy_class = ?,
                agence_id = ?
             WHERE estate_id = ? AND agent_id = ?'
        );

        return $stmt->execute([
            $row['estate_country'],
            $row['estate_address'],
            $row['estate_type'],
            $row['price'],
            $row['surface'],
            $row['rooms_count'],
            $row['bedrooms_count'],
            $row['description'],
            $row['estate_status'],
            $row['energy_class'],
            $row['agence_id'],
            $estateId,
            $agentId,
        ]);
    }
}
