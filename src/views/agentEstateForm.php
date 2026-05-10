<?php
$page_title = (!empty($data['estate']) ? 'Modifier le bien' : 'Nouvelle annonce') . ' — Ymmo';
$e = $data['estate'] ?? [];
$old = $data['old'] ?? [];
$gv = static function (string $key, $default = '') use ($e, $old): string {
    $v = $old[$key] ?? $e[$key] ?? $default;

    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
};
$errors = $data['errors'] ?? [];
$agences = $data['agences'] ?? [];
$is_edit = !empty($data['estate']);
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container">
        <p class="lux-overline"><?= $is_edit ? 'Édition' : 'Publication' ?></p>
        <h1 class="lux-title"><?= $is_edit ? 'Modifier <em>l’annonce</em>' : 'Nouvelle <em>annonce</em>' ?></h1>

        <?php if (!empty($errors)): ?>
            <ul class="lux-errors">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form class="lux-form" method="post" action="<?= $is_edit ? '/agent/biens/editer/' . (int) ($e['estate_id'] ?? 0) : '/agent/biens/nouveau' ?>">
            <?php require __DIR__ . '/partials/csrf_field.php'; ?>
            <div class="lux-field">
                <label for="estate_country">Pays</label>
                <input class="lux-input" name="estate_country" id="estate_country" value="<?= $gv('estate_country', 'France') ?>" required>
            </div>
            <div class="lux-field">
                <label for="estate_address">Adresse complète</label>
                <input class="lux-input" name="estate_address" id="estate_address" value="<?= $gv('estate_address') ?>" required>
            </div>
            <div class="lux-field">
                <label for="estate_type">Type de bien</label>
                <input class="lux-input" name="estate_type" id="estate_type" value="<?= $gv('estate_type') ?>" required placeholder="Appartement, Maison…">
            </div>
            <div class="lux-field">
                <label for="price">Prix (€)</label>
                <input class="lux-input" type="number" step="0.01" min="0" name="price" id="price" value="<?= $gv('price') ?>" required>
            </div>
            <div class="lux-field">
                <label for="surface">Surface (m²)</label>
                <input class="lux-input" type="number" min="1" name="surface" id="surface" value="<?= $gv('surface') ?>" required>
            </div>
            <div class="lux-field">
                <label for="rooms_count">Pièces</label>
                <input class="lux-input" type="number" min="1" name="rooms_count" id="rooms_count" value="<?= $gv('rooms_count') ?>" required>
            </div>
            <div class="lux-field">
                <label for="bedrooms_count">Chambres</label>
                <input class="lux-input" type="number" min="0" name="bedrooms_count" id="bedrooms_count" value="<?= $gv('bedrooms_count') ?>">
            </div>
            <div class="lux-field">
                <label for="energy_class">Classe énergie (A–G)</label>
                <input class="lux-input" name="energy_class" id="energy_class" maxlength="3" value="<?= $gv('energy_class') ?>">
            </div>
            <div class="lux-field">
                <label for="estate_status">Statut annonce</label>
                <select class="lux-input" name="estate_status" id="estate_status">
                    <?php
                    $st = $old['estate_status'] ?? $e['estate_status'] ?? 'available';
                    foreach (['available' => 'Disponible', 'reserved' => 'Réservé', 'sold' => 'Vendu', 'withdrawn' => 'Retiré'] as $val => $lbl): ?>
                        <option value="<?= $val ?>" <?= ($st === $val) ? 'selected' : '' ?>><?= $lbl ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="lux-field">
                <label for="agence_id">Agence</label>
                <select class="lux-input" name="agence_id" id="agence_id" required>
                    <option value="">—</option>
                    <?php
                    $sel = (string) ($old['agence_id'] ?? $e['agence_id'] ?? '');
                    foreach ($agences as $a): ?>
                        <option value="<?= (int) ($a['agence_id'] ?? 0) ?>" <?= ($sel !== '' && (int) $sel === (int) ($a['agence_id'] ?? 0)) ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string) ($a['agence_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="lux-field">
                <label for="description">Description</label>
                <textarea class="lux-input" name="description" id="description" rows="6"><?= $gv('description') ?></textarea>
            </div>
            <div class="lux-btn-row">
                <button class="lux-btn lux-btn--primary" type="submit"><span>Enregistrer</span></button>
                <a class="lux-link" href="/agent/biens">Annuler</a>
            </div>
        </form>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
