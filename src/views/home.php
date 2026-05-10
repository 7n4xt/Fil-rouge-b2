<?php
/* Legacy home view: HomeController redirects to /properties */
$page_title = 'Ymmo';
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container">
        <p class="lux-overline">Ymmo</p>
        <h1 class="lux-title">La collection <em>vous attend</em></h1>
        <p class="lux-lede">Cette page est un point d'entrée secondaire. La collection principale se trouve sous /properties.</p>
        <a class="lux-btn lux-btn--primary" href="/properties"><span>Voir les biens</span></a>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
