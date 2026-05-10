<?php
$http_error_code = $http_error_code ?? 500;
$http_error_title = $http_error_title ?? 'Erreur';
$http_error_message = $http_error_message ?? 'Une erreur est survenue.';
$page_title = $http_error_title . ' — Ymmo';
require __DIR__ . '/partials/layout_start.php';
?>
<main class="lux-static">
    <div>
        <p class="lux-static__code"><?= (int) $http_error_code ?></p>
        <h1><?= htmlspecialchars($http_error_title, ENT_QUOTES, 'UTF-8') ?></h1>
        <p><?= htmlspecialchars($http_error_message, ENT_QUOTES, 'UTF-8') ?></p>
        <div class="lux-static-actions">
            <a class="lux-btn lux-btn--primary" href="/properties"><span>Collection</span></a>
            <?php if (($http_error_code ?? 0) === 401): ?>
                <a class="lux-btn lux-btn--secondary" href="/login"><span>Connexion</span></a>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
