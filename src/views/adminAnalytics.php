<?php
$page_title = 'Marché & aide à la décision — Ymmo';
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container">
        <p class="lux-overline">Données & stratégie</p>
        <h1 class="lux-title">Analyse <em>marché</em></h1>
        <p class="lux-lede">Indicateurs agrégés à partir du stock et des transactions — base pour tendances et décisions commerciales.</p>

        <section class="lux-panel">
            <h2 class="lux-overline">Synthèse automatique (règles métier)</h2>
            <ul class="lux-prose">
                <?php foreach (($data['insights'] ?? []) as $line): ?>
                    <li><?= htmlspecialchars($line, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="lux-panel lux-section-spaced">
            <h2 class="lux-overline">Prix moyen par type de bien</h2>
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Type</th><th>Prix moyen</th><th>Annonces</th></tr></thead>
                    <tbody>
                    <?php foreach (($data['avg_by_type'] ?? []) as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) ($row['type'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= number_format((float) ($row['avg_price'] ?? 0), 0, ',', ' ') ?> €</td>
                            <td><?= (int) ($row['cnt'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['avg_by_type'])): ?><tr><td colspan="3">Pas assez de données.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="lux-panel lux-section-spaced">
            <h2 class="lux-overline">Prix au m² moyen par type</h2>
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Type</th><th>€ / m² (moyenne)</th></tr></thead>
                    <tbody>
                    <?php foreach (($data['sqm_by_type'] ?? []) as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) ($row['type'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= number_format((float) ($row['avg_sqm_price'] ?? 0), 0, ',', ' ') ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['sqm_by_type'])): ?><tr><td colspan="2">Pas assez de données.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="lux-panel lux-section-spaced">
            <h2 class="lux-overline">Transactions récentes (volume par mois)</h2>
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Mois</th><th>Nombre</th><th>Volume</th></tr></thead>
                    <tbody>
                    <?php foreach (($data['transactions_monthly'] ?? []) as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) ($row['ym'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int) ($row['cnt'] ?? 0) ?></td>
                            <td><?= number_format((float) ($row['volume'] ?? 0), 0, ',', ' ') ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['transactions_monthly'])): ?><tr><td colspan="3">Aucune transaction sur la période.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="lux-panel lux-section-spaced">
            <h2 class="lux-overline">Stock par pays</h2>
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Pays</th><th>Annonces</th><th>Prix moyen</th></tr></thead>
                    <tbody>
                    <?php foreach (($data['stock_by_country'] ?? []) as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) ($row['estate_country'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int) ($row['cnt'] ?? 0) ?></td>
                            <td><?= number_format((float) ($row['avg_price'] ?? 0), 0, ',', ' ') ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['stock_by_country'])): ?><tr><td colspan="3">Pas de données.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="lux-panel lux-section-spaced">
            <h2 class="lux-overline">Répartition diagnostics énergie</h2>
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Classe</th><th>Annonces</th></tr></thead>
                    <tbody>
                    <?php foreach (($data['energy_mix'] ?? []) as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) ($row['energy_class'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int) ($row['cnt'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['energy_mix'])): ?><tr><td colspan="2">Pas de données.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="lux-btn-row lux-actions-footer">
            <a class="lux-btn lux-btn--secondary" href="/admin"><span>Tableau de bord admin</span></a>
            <a class="lux-link" href="/properties">Collection publique</a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
