<?php
$page_title = 'Mon espace — Ymmo';
$step_labels = [
    'contact' => 'Premier contact',
    'visite' => 'Visite',
    'offre' => 'Offre',
    'compromis' => 'Compromis',
    'acte' => 'Signature acte',
    'termine' => 'Terminé',
];
$status_labels = [
    'nouveau' => 'Nouveau',
    'en_cours' => 'En cours',
    'traite' => 'Traité',
    'annule' => 'Annulé',
];
$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container">
        <p class="lux-overline">Espace client</p>
        <h1 class="lux-title">Vos <em>démarches</em></h1>
        <p class="lux-lede">Demandes d’information ou de visite, et suivi des dossiers ouverts avec un conseiller.</p>

        <?php if ($flash_success): ?><p class="lux-flash"><?= htmlspecialchars($flash_success, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <?php if ($flash_error): ?><ul class="lux-errors"><li><?= htmlspecialchars($flash_error, ENT_QUOTES, 'UTF-8') ?></li></ul><?php endif; ?>

        <section class="lux-panel lux-section-spaced">
            <h2 class="lux-overline">Demandes envoyées</h2>
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Date</th><th>Bien</th><th>Type</th><th>Statut</th></tr></thead>
                    <tbody>
                    <?php foreach ($data['requests'] as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars(substr((string) ($r['created_at'] ?? ''), 0, 16), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string) ($r['estate_address'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= ($r['request_kind'] ?? '') === 'visite' ? 'Visite' : 'Information' ?></td>
                            <td><?= htmlspecialchars($status_labels[$r['status'] ?? ''] ?? (string) ($r['status'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['requests'])): ?><tr><td colspan="4">Aucune demande pour l’instant.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="lux-panel lux-section-spaced">
            <h2 class="lux-overline">Dossiers en cours</h2>
            <div class="lux-table-wrap">
                <table class="lux-table">
                    <thead><tr><th>Mise à jour</th><th>Titre</th><th>Bien</th><th>Type</th><th>Étape</th><th>Conseiller</th><th>Note</th></tr></thead>
                    <tbody>
                    <?php foreach ($data['dossiers'] as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars(substr((string) ($d['updated_at'] ?? ''), 0, 16), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string) ($d['title'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string) ($d['estate_address'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (($d['flow_type'] ?? '') === 'vente') ? 'Vente' : 'Achat' ?></td>
                            <td><?= htmlspecialchars($step_labels[$d['step'] ?? ''] ?? (string) ($d['step'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(trim(($d['agent_first_name'] ?? '') . ' ' . ($d['agent_last_name'] ?? '')), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= $d['notes_public'] ? nl2br(htmlspecialchars((string) $d['notes_public'], ENT_QUOTES, 'UTF-8')) : '—' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($data['dossiers'])): ?><tr><td colspan="7">Aucun dossier suivi pour le moment.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="lux-btn-row">
            <a class="lux-btn lux-btn--secondary" href="/properties"><span>Retour à la collection</span></a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
