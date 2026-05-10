<?php
$errors = $errors ?? [];
$flash_success = $flash_success ?? null;
$page_title = 'Connexion — Ymmo';
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container lux-auth-layout">
        <span class="lux-vertical-label" aria-hidden="true">Editorial / Vol. 01</span>
        <div>
            <p class="lux-overline">Accès membre</p>
            <h1 class="lux-title">Bienvenue <em>de retour</em></h1>
            <p class="lux-lede">Connectez-vous pour poursuivre votre parcours dans la collection de biens Ymmo.</p>
            <?php if ($flash_success): ?>
                <p class="lux-flash"><?= htmlspecialchars($flash_success, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <?php if (!empty($flash_error_redirect)): ?>
                <ul class="lux-errors"><li><?= htmlspecialchars($flash_error_redirect, ENT_QUOTES, 'UTF-8') ?></li></ul>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <ul class="lux-errors">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form class="lux-form" method="post" action="/login" autocomplete="on">
                <?php require __DIR__ . '/partials/csrf_field.php'; ?>
                <div class="lux-field">
                    <label for="mail">Courriel</label>
                    <input class="lux-input" type="email" name="mail" id="mail" placeholder="votre adresse" required>
                </div>
                <div class="lux-field">
                    <label for="password">Mot de passe</label>
                    <input class="lux-input" type="password" name="password" id="password" placeholder="votre secret" required>
                </div>
                <div class="lux-btn-row">
                    <button class="lux-btn lux-btn--primary" type="submit"><span>Se connecter</span></button>
                    <a class="lux-link" href="/register">Créer un compte</a>
                </div>
            </form>
        </div>
        <aside class="lux-auth-aside">
            <p class="lux-overline">Après connexion</p>
            <p class="lux-lede lux-lede--flush">Les comptes <strong>client</strong> arrivent sur <code class="lux-code">/account</code> ; les autres profils sur la collection.</p>
        </aside>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
