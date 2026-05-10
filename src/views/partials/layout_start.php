<?php
$page_title = $page_title ?? 'Ymmo';
$body_class = $body_class ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/luxury.css">
</head>
<body class="lux-body <?= htmlspecialchars($body_class, ENT_QUOTES, 'UTF-8') ?>">
<div class="lux-noise" aria-hidden="true"></div>
<div class="lux-grid-lines" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
