<?php
$page_title = 'Mes biens — Ymmo';
$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container">
        <p class="lux-overline">Mandats</p>
        <h1 class="lux-title">Mes <em>annonces</em></h1>
        <p class="lux-lede">Ajout et modification des biens dont vous êtes l’agent référent.</p>

        <?php if ($flash_success): ?><p class="lux-flash"><?= htmlspecialchars($flash_success, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <?php if ($flash_error): ?><ul class="lux-errors"><li><?= htmlspecialchars($flash_error, ENT_QUOTES, 'UTF-8') ?></li></ul><?php endif; ?>

        <div class="lux-btn-row">
            <a class="lux-btn lux-btn--primary" href="/agent/biens/nouveau"><span>Nouvelle annonce</span></a>
            <a class="lux-link" href="/agent">Retour tableau de bord</a>
        </div>

        <section class="lux-panel lux-section-spaced">
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Adresse</th><th>Type</th><th>Prix</th><th>Surface</th><th>Statut</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($data['estates'] as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) ($row['estate_address'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string) ($row['estate_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= number_format((float) ($row['price'] ?? 0), 0, ',', ' ') ?> €</td>
                            <td><?= (int) ($row['surface'] ?? 0) ?> m²</td>
                            <td><?= htmlspecialchars((string) ($row['estate_status'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <a class="lux-link" href="/properties/<?= (int) $row['estate_id'] ?>">Voir</a>
                                · <a class="lux-link" href="/agent/biens/editer/<?= (int) $row['estate_id'] ?>">Modifier</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['estates'])): ?><tr><td colspan="6">Aucun bien pour ce compte.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
