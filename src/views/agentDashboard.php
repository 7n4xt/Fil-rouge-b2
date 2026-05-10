<?php
$page_title = 'Espace agent — Ymmo';
$s = $data['summary'];
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container">
        <p class="lux-overline">Mon activité</p>
        <h1 class="lux-title">Portefeuille <em>mandats</em></h1>
        <p class="lux-lede">Synthèse de vos annonces et des opérations liées à vos biens.</p>

        <div class="lux-btn-row lux-section-spaced">
            <a class="lux-btn lux-btn--secondary" href="/agent/biens"><span>Mes biens</span></a>
            <a class="lux-btn lux-btn--secondary" href="/agent/biens/nouveau"><span>Nouvelle annonce</span></a>
            <a class="lux-btn lux-btn--secondary" href="/agent/demandes"><span>Demandes clients</span></a>
            <a class="lux-btn lux-btn--secondary" href="/agent/dossiers"><span>Dossiers</span></a>
        </div>

        <div class="lux-dash-stats">
            <div class="lux-stat"><p class="lux-stat__value"><?= (int) $s['listing_count'] ?></p><p class="lux-stat__label">Mandats actifs</p></div>
            <div class="lux-stat"><p class="lux-stat__value"><?= number_format($s['avg_price'], 0, ',', ' ') ?> €</p><p class="lux-stat__label">Prix moyen</p></div>
            <div class="lux-stat"><p class="lux-stat__value"><?= number_format($s['portfolio_value'], 0, ',', ' ') ?> €</p><p class="lux-stat__label">Valorisation cumulée</p></div>
        </div>

        <section class="lux-panel">
            <h2 class="lux-overline">Derniers biens</h2>
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Adresse</th><th>Type</th><th>Prix</th><th>Statut</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($data['listings'] as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['estate_address'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['estate_type'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= number_format((float) ($row['price'] ?? 0), 0, ',', ' ') ?> €</td>
                            <td><?= htmlspecialchars($row['estate_status'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><a class="lux-link" href="/properties/<?= (int) $row['estate_id'] ?>">Fiche</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['listings'])): ?><tr><td colspan="5">Aucun bien rattaché à votre compte</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="lux-panel lux-section-spaced">
            <h2 class="lux-overline">Transactions sur vos biens</h2>
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Date</th><th>Type</th><th>Montant</th><th>Bien</th></tr></thead>
                    <tbody>
                    <?php foreach ($data['sales'] as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars(substr($row['transaction_date'] ?? '', 0, 16), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['transaction_type'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= number_format((float) ($row['price_final'] ?? 0), 0, ',', ' ') ?> €</td>
                            <td><?= htmlspecialchars($row['estate_address'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['sales'])): ?><tr><td colspan="4">Aucune transaction enregistrée</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="lux-btn-row lux-section-spaced">
            <a class="lux-btn lux-btn--secondary" href="/properties"><span>Collection</span></a>
            <a class="lux-link" href="/logout">Déconnexion</a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
