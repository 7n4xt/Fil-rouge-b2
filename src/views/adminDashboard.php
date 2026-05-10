<?php
$page_title = 'Administration — Ymmo';
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container">
        <p class="lux-overline">Espace réservé</p>
        <h1 class="lux-title">Tableau <em>administrateur</em></h1>
        <p class="lux-lede">Bienvenue. Les modules métier seront branchés ici selon la répartition d'équipe.</p>
        <div class="lux-btn-row">
            <a class="lux-btn lux-btn--secondary" href="/properties"><span>Collection</span></a>
            <a class="lux-link" href="/logout">Déconnexion</a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
