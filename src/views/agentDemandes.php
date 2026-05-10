<?php
$page_title = 'Demandes clients — Ymmo';
$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container">
        <p class="lux-overline">Relation clients</p>
        <h1 class="lux-title">Demandes <em>infos & visites</em></h1>
        <p class="lux-lede">Demandes sur vos mandats : statut et notes internes.</p>

        <?php if ($flash_success): ?><p class="lux-flash"><?= htmlspecialchars($flash_success, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <?php if ($flash_error): ?><ul class="lux-errors"><li><?= htmlspecialchars($flash_error, ENT_QUOTES, 'UTF-8') ?></li></ul><?php endif; ?>

        <?php foreach ($data['requests'] as $r): ?>
            <article class="lux-panel lux-section-spaced">
                <p class="lux-overline"><?= htmlspecialchars(substr((string) ($r['created_at'] ?? ''), 0, 16), ENT_QUOTES, 'UTF-8') ?></p>
                <h2 class="lux-title lux-title-sub"><?= htmlspecialchars((string) ($r['estate_address'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
                <p class="lux-lede">
                    <?= htmlspecialchars(trim(($r['client_first_name'] ?? '') . ' ' . ($r['client_last_name'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
                    — <?= htmlspecialchars((string) ($r['client_mail'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </p>
                <p>Type : <strong><?= ($r['request_kind'] ?? '') === 'visite' ? 'Visite' : 'Information' ?></strong>
                    <?php if (!empty($r['preferred_visit_at'])): ?>
                        · Créneau souhaité : <?= htmlspecialchars(substr((string) $r['preferred_visit_at'], 0, 16), ENT_QUOTES, 'UTF-8') ?>
                    <?php endif; ?>
                </p>
                <?php if (!empty($r['message'])): ?>
                    <div class="lux-prose"><p><?= nl2br(htmlspecialchars((string) $r['message'], ENT_QUOTES, 'UTF-8')) ?></p></div>
                <?php endif; ?>

                <form class="lux-form lux-form--compact lux-section-spaced" method="post" action="/agent/demandes">
                    <?php require __DIR__ . '/partials/csrf_field.php'; ?>
                    <input type="hidden" name="lead_request_id" value="<?= (int) ($r['lead_request_id'] ?? 0) ?>">
                    <div class="lux-field">
                        <label for="status_<?= (int) ($r['lead_request_id'] ?? 0) ?>">Statut</label>
                        <select class="lux-input" name="status" id="status_<?= (int) ($r['lead_request_id'] ?? 0) ?>">
                            <?php foreach (['nouveau', 'en_cours', 'traite', 'annule'] as $st): ?>
                                <option value="<?= $st ?>" <?= (($r['status'] ?? '') === $st) ? 'selected' : '' ?>><?= $st ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="lux-field">
                        <label for="note_<?= (int) ($r['lead_request_id'] ?? 0) ?>">Note interne</label>
                        <textarea class="lux-input" name="agent_note" id="note_<?= (int) ($r['lead_request_id'] ?? 0) ?>" rows="2"><?= htmlspecialchars((string) ($r['agent_note'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                    <button class="lux-btn lux-btn--secondary" type="submit"><span>Enregistrer</span></button>
                </form>
            </article>
        <?php endforeach; ?>

        <?php if (empty($data['requests'])): ?>
            <p class="lux-lede">Aucune demande pour vos mandats.</p>
        <?php endif; ?>

        <div class="lux-btn-row lux-section-spaced">
            <a class="lux-link" href="/agent">Retour tableau de bord</a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
