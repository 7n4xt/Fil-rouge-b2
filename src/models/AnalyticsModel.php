<?php

declare(strict_types=1);

/**
 * Agrégats marché + indicateurs pour aide à la décision (règles métier, évolutif vers IA externe).
 */
class AnalyticsModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** @return list<array{type:string,avg_price:float,cnt:int}> */
    public function averagePriceByType(): array
    {
        $stmt = $this->pdo->query(
            "SELECT estate_type AS type, AVG(price) AS avg_price, COUNT(*) AS cnt
             FROM estate WHERE estate_status = 'available' AND estate_type IS NOT NULL AND estate_type <> ''
             GROUP BY estate_type ORDER BY avg_price DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /** Prix au m² médian par type (approximation : AVG(price/surface)) */
    /** @return list<array{type:string,avg_sqm_price:float}> */
    public function avgPricePerSqmByType(): array
    {
        $stmt = $this->pdo->query(
            "SELECT estate_type AS type,
                    AVG(price / NULLIF(surface, 0)) AS avg_sqm_price
             FROM estate
             WHERE estate_status = 'available' AND surface > 0
             GROUP BY estate_type"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /** @return list<array{ym:string,cnt:int,volume:float}> */
    public function transactionsLastMonths(int $months = 6): array
    {
        $months = max(1, min(24, $months));
        $stmt = $this->pdo->prepare(
            "SELECT DATE_FORMAT(transaction_date, '%Y-%m') AS ym,
                    COUNT(*) AS cnt,
                    COALESCE(SUM(price_final), 0) AS volume
             FROM transaction
             WHERE transaction_date >= DATE_SUB(CURRENT_DATE, INTERVAL ? MONTH)
             GROUP BY ym ORDER BY ym ASC"
        );
        $stmt->execute([$months]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /** @return list<array{estate_country:string,cnt:int,avg_price:float}> */
    public function stockByCountry(): array
    {
        $stmt = $this->pdo->query(
            "SELECT COALESCE(estate_country, '—') AS estate_country, COUNT(*) AS cnt, AVG(price) AS avg_price
             FROM estate WHERE estate_status = 'available'
             GROUP BY estate_country ORDER BY cnt DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /** @return list<array{energy_class:string,cnt:int}> */
    public function energyMix(): array
    {
        $stmt = $this->pdo->query(
            "SELECT COALESCE(NULLIF(energy_class, ''), 'NC') AS energy_class, COUNT(*) AS cnt
             FROM estate WHERE estate_status = 'available'
             GROUP BY energy_class ORDER BY cnt DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Textes d’aide à la décision dérivés des données (pas de modèle ML : structure prête pour enrichissement API).
     *
     * @param array<string, mixed> $snapshot
     * @return list<string>
     */
    public function buildInsights(array $snapshot): array
    {
        $lines = [];
        $byType = $snapshot['avg_by_type'] ?? [];
        $sqm = $snapshot['sqm_by_type'] ?? [];
        $tx = $snapshot['transactions_monthly'] ?? [];

        if ($byType !== []) {
            $top = $byType[0];
            $lines[] = sprintf(
                'Segment le plus cher en prix moyen : %s (~%s € pour %s annonces).',
                $top['type'] ?? '?',
                number_format((float) ($top['avg_price'] ?? 0), 0, ',', ' '),
                (string) (int) ($top['cnt'] ?? 0)
            );
        }

        foreach ($sqm as $row) {
            if (($row['type'] ?? '') === 'Appartement' && ($row['avg_sqm_price'] ?? 0) > 0) {
                $lines[] = sprintf(
                    'Référence appartements : prix moyen au m² autour de %s €/m² — utile pour positionner une offre.',
                    number_format((float) $row['avg_sqm_price'], 0, ',', ' ')
                );
                break;
            }
        }

        if (count($tx) >= 2) {
            $last = end($tx);
            $prev = $tx[count($tx) - 2];
            $lastCnt = (int) ($last['cnt'] ?? 0);
            $prevCnt = (int) ($prev['cnt'] ?? 0);
            if ($prevCnt > 0 && $lastCnt !== $prevCnt) {
                $delta = round(($lastCnt - $prevCnt) / $prevCnt * 100, 1);
                $lines[] = $delta >= 0
                    ? sprintf('Activité transactions : hausse d’environ %s %% du mois précédent au dernier mois observé.', $delta)
                    : sprintf('Activité transactions : baisse d’environ %s %% sur la même période.', abs($delta));
            }
        }

        $energy = $snapshot['energy_mix'] ?? [];
        $low = 0;
        foreach ($energy as $e) {
            $cls = (string) ($e['energy_class'] ?? '');
            if (in_array($cls, ['A', 'B'], true)) {
                $low += (int) ($e['cnt'] ?? 0);
            }
        }
        $totalE = array_sum(array_column($energy, 'cnt'));
        if ($totalE > 0 && $low > 0) {
            $pct = round($low / $totalE * 100);
            $lines[] = sprintf(
                'Environ %s %% du parc annoncé est classé A ou B — argument fort pour les biens performants.',
                (string) $pct
            );
        }

        if ($lines === []) {
            $lines[] = 'Ajoutez plus de biens et de transactions pour affiner les tendances et recommandations.';
        }

        $lines[] = 'Projection IA : branchement possible vers un service externe (scoring, séries temporelles) sans changer les écrans.';

        return $lines;
    }

    public function snapshot(): array
    {
        $avgByType = $this->averagePriceByType();
        $sqmByType = $this->avgPricePerSqmByType();
        $transactionsMonthly = $this->transactionsLastMonths(6);
        $stockByCountry = $this->stockByCountry();
        $energyMix = $this->energyMix();

        return [
            'avg_by_type' => $avgByType,
            'sqm_by_type' => $sqmByType,
            'transactions_monthly' => $transactionsMonthly,
            'stock_by_country' => $stockByCountry,
            'energy_mix' => $energyMix,
            'insights' => $this->buildInsights([
                'avg_by_type' => $avgByType,
                'sqm_by_type' => $sqmByType,
                'transactions_monthly' => $transactionsMonthly,
                'energy_mix' => $energyMix,
            ]),
        ];
    }
}
