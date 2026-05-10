<?php
$errors = $errors ?? [];
$old = $old ?? [];
$page_title = 'Inscription — Ymmo';
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container lux-auth-layout">
        <span class="lux-vertical-label" aria-hidden="true">Nouveau / Vol. 02</span>
        <div>
            <p class="lux-overline">Rejoindre Ymmo</p>
            <h1 class="lux-title">Créer votre <em>profil</em></h1>
            <p class="lux-lede">Un compte vous permet de suivre vos démarches et d’accéder à l’espace dédié selon votre rôle.</p>
            <?php if (!empty($errors)): ?>
                <ul class="lux-errors">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form class="lux-form" method="post" action="/register" autocomplete="on">
                <div class="lux-field">
                    <label for="first_name">Prénom</label>
                    <input class="lux-input" name="first_name" id="first_name" placeholder="prénom" value="<?= htmlspecialchars($old['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="lux-field">
                    <label for="last_name">Nom</label>
                    <input class="lux-input" name="last_name" id="last_name" placeholder="nom" value="<?= htmlspecialchars($old['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="lux-field">
                    <label for="mail">Courriel</label>
                    <input class="lux-input" type="email" name="mail" id="mail" placeholder="adresse électronique" value="<?= htmlspecialchars($old['mail'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="lux-field">
                    <label for="phone_number">Téléphone</label>
                    <input class="lux-input" name="phone_number" id="phone_number" placeholder="facultatif" value="<?= htmlspecialchars($old['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="lux-field">
                    <label for="password">Mot de passe</label>
                    <input class="lux-input" type="password" name="password" id="password" placeholder="8 caractères minimum" required>
                </div>
                <div class="lux-field">
                    <label for="password_confirm">Confirmation</label>
                    <input class="lux-input" type="password" name="password_confirm" id="password_confirm" placeholder="répéter le mot de passe" required>
                </div>
                <div class="lux-btn-row">
                    <button class="lux-btn lux-btn--primary" type="submit"><span>Créer le compte</span></button>
                    <a class="lux-link" href="/login">Déjà inscrit ? Connexion</a>
                </div>
            </form>
        </div>
        <aside class="lux-auth-aside">
            <p class="lux-overline">Confidentialité</p>
            <p class="lux-lede lux-lede--flush">Votre mot de passe est stocké de façon sécurisée. Ne partagez jamais vos identifiants.</p>
        </aside>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
