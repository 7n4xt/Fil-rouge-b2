<?php
$page_title = 'Administration — Ymmo';
$s = $data['summary'];
$dash_stats = [
    ['Utilisateurs', (string) (int) $s['user_count']], ['Agents', (string) (int) $s['agent_count']], ['Biens', (string) (int) $s['estate_count']],
    ['Agences', (string) (int) $s['agence_count']], ['Transactions', (string) (int) $s['transaction_count']],
    ['Volume signé', number_format($s['transaction_volume'], 0, ',', ' ') . ' €'], ['Prix moyen bien', number_format($s['avg_estate_price'], 0, ',', ' ') . ' €'],
];
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container">
        <p class="lux-overline">Pilotage</p>
        <h1 class="lux-title">Vue <em>globale</em></h1>
        <p class="lux-lede">Indicateurs consolidés sur les utilisateurs, les biens et l’activité transactionnelle.</p>

        <div class="lux-dash-stats">
            <?php foreach ($dash_stats as [$lbl, $val]): ?>
                <div class="lux-stat"><p class="lux-stat__value"><?= htmlspecialchars($val, ENT_QUOTES, 'UTF-8') ?></p><p class="lux-stat__label"><?= htmlspecialchars($lbl, ENT_QUOTES, 'UTF-8') ?></p></div>
            <?php endforeach; ?>
        </div>

        <section class="lux-panel">
            <h2 class="lux-overline">Statuts des biens</h2>
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Statut</th><th>Nombre</th></tr></thead>
                    <tbody>
                    <?php foreach ($data['status_breakdown'] as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['estate_status'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int) $row['cnt'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['status_breakdown'])): ?><tr><td colspan="2">Aucune donnée</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="lux-panel lux-section-spaced">
            <h2 class="lux-overline">Agences — biens listés</h2>
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Agence</th><th>Biens</th></tr></thead>
                    <tbody>
                    <?php foreach ($data['top_agencies'] as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['agence_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int) $row['estate_count'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['top_agencies'])): ?><tr><td colspan="2">Aucune agence</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="lux-panel lux-section-spaced">
            <h2 class="lux-overline">Transactions récentes</h2>
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Date</th><th>Type</th><th>Montant</th><th>Bien</th></tr></thead>
                    <tbody>
                    <?php foreach ($data['recent_transactions'] as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars(substr($row['transaction_date'] ?? '', 0, 16), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['transaction_type'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= number_format((float) ($row['price_final'] ?? 0), 0, ',', ' ') ?> €</td>
                            <td><?= htmlspecialchars($row['estate_address'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['recent_transactions'])): ?><tr><td colspan="4">Aucune transaction</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="lux-btn-row lux-actions-footer">
            <a class="lux-btn lux-btn--secondary" href="/properties"><span>Collection</span></a>
            <a class="lux-link" href="/logout">Déconnexion</a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
