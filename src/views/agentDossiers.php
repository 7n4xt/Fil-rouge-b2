<?php
$page_title = 'Dossiers clients — Ymmo';
$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
$step_labels = [
    'contact' => 'Premier contact',
    'visite' => 'Visite',
    'offre' => 'Offre',
    'compromis' => 'Compromis',
    'acte' => 'Signature acte',
    'termine' => 'Terminé',
];
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container">
        <p class="lux-overline">Commercialisation</p>
        <h1 class="lux-title">Dossiers <em>clients</em></h1>
        <p class="lux-lede">Ouverture d’un dossier achat/vente et mise à jour de l’avancement visible par le client.</p>

        <?php if ($flash_success): ?><p class="lux-flash"><?= htmlspecialchars($flash_success, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <?php if ($flash_error): ?><ul class="lux-errors"><li><?= htmlspecialchars($flash_error, ENT_QUOTES, 'UTF-8') ?></li></ul><?php endif; ?>

        <section class="lux-panel">
            <h2 class="lux-overline">Nouveau dossier</h2>
            <form class="lux-form" method="post" action="/agent/dossiers">
                <?php require __DIR__ . '/partials/csrf_field.php'; ?>
                <input type="hidden" name="create_dossier" value="1">
                <div class="lux-field">
                    <label for="client_mail">Courriel du client</label>
                    <input class="lux-input" type="email" name="client_mail" id="client_mail" required placeholder="compte client existant">
                </div>
                <div class="lux-field">
                    <label for="estate_id">ID bien (optionnel)</label>
                    <input class="lux-input" type="number" name="estate_id" id="estate_id" min="1" placeholder="laisser vide si non défini">
                </div>
                <div class="lux-field">
                    <label for="flow_type">Flux</label>
                    <select class="lux-input" name="flow_type" id="flow_type">
                        <option value="achat">Achat</option>
                        <option value="vente">Vente</option>
                    </select>
                </div>
                <div class="lux-field">
                    <label for="title">Titre du dossier</label>
                    <input class="lux-input" type="text" name="title" id="title" placeholder="ex. Projet famille Dupont">
                </div>
                <div class="lux-field">
                    <label for="notes_public">Note visible client</label>
                    <textarea class="lux-input" name="notes_public" id="notes_public" rows="2"></textarea>
                </div>
                <button class="lux-btn lux-btn--primary" type="submit"><span>Créer le dossier</span></button>
            </form>
        </section>

        <?php foreach ($data['dossiers'] as $d): ?>
            <article class="lux-panel lux-section-spaced">
                <p class="lux-overline"><?= htmlspecialchars((string) ($d['flow_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?> · <?= htmlspecialchars(substr((string) ($d['updated_at'] ?? ''), 0, 16), ENT_QUOTES, 'UTF-8') ?></p>
                <h2 class="lux-title lux-title-sub"><?= htmlspecialchars((string) ($d['title'] ?? 'Dossier'), ENT_QUOTES, 'UTF-8') ?></h2>
                <p class="lux-lede">
                    Client : <?= htmlspecialchars(trim(($d['client_first_name'] ?? '') . ' ' . ($d['client_last_name'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
                    (<?= htmlspecialchars((string) ($d['client_mail'] ?? ''), ENT_QUOTES, 'UTF-8') ?>)
                </p>
                <p>Bien : <?= htmlspecialchars((string) ($d['estate_address'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></p>

                <form class="lux-form lux-form--compact lux-section-spaced" method="post" action="/agent/dossiers">
                    <?php require __DIR__ . '/partials/csrf_field.php'; ?>
                    <input type="hidden" name="dossier_id" value="<?= (int) ($d['dossier_id'] ?? 0) ?>">
                    <div class="lux-field">
                        <label for="step_<?= (int) ($d['dossier_id'] ?? 0) ?>">Étape</label>
                        <select class="lux-input" name="step" id="step_<?= (int) ($d['dossier_id'] ?? 0) ?>">
                            <?php foreach ($data['steps'] as $st): ?>
                                <option value="<?= htmlspecialchars($st, ENT_QUOTES, 'UTF-8') ?>" <?= (($d['step'] ?? '') === $st) ? 'selected' : '' ?>><?= htmlspecialchars($step_labels[$st] ?? $st, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="lux-field">
                        <label for="np_<?= (int) ($d['dossier_id'] ?? 0) ?>">Note client</label>
                        <textarea class="lux-input" name="notes_public" id="np_<?= (int) ($d['dossier_id'] ?? 0) ?>" rows="2"><?= htmlspecialchars((string) ($d['notes_public'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                    <div class="lux-field">
                        <label for="ni_<?= (int) ($d['dossier_id'] ?? 0) ?>">Note interne</label>
                        <textarea class="lux-input" name="notes_internal" id="ni_<?= (int) ($d['dossier_id'] ?? 0) ?>" rows="2"><?= htmlspecialchars((string) ($d['notes_internal'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                    <button class="lux-btn lux-btn--secondary" type="submit"><span>Mettre à jour</span></button>
                </form>
            </article>
        <?php endforeach; ?>

        <?php if (empty($data['dossiers'])): ?>
            <p class="lux-lede lux-section-spaced">Aucun dossier pour l’instant.</p>
        <?php endif; ?>

        <div class="lux-btn-row lux-section-spaced">
            <a class="lux-link" href="/agent">Retour tableau de bord</a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
