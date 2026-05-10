<?php
$page_title = 'Erreur — Ymmo';
require __DIR__ . '/partials/layout_start.php';
?>
<main class="lux-static">
    <div>
        <p class="lux-static__code">500</p>
        <h1>Incident serveur</h1>
        <p>Une erreur inattendue s'est produite. Veuillez réessayer plus tard.</p>
        <a class="lux-btn lux-btn--primary" href="/properties"><span>Retour à la collection</span></a>
    </div>
</main>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
