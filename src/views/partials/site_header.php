<?php
$nav_role = $_SESSION['role'] ?? null;
$nav_first_name = $_SESSION['first_name'] ?? null;
?>
<header class="lux-header">
    <div class="lux-container lux-header__inner">
        <a class="lux-logo" href="/properties">Ymmo</a>
        <nav class="lux-nav" aria-label="Principal">
            <a class="lux-nav__link" href="/properties">Collection</a>
            <?php if ($nav_first_name): ?>
                <span class="lux-nav__meta"><?= htmlspecialchars($nav_first_name, ENT_QUOTES, 'UTF-8') ?></span>
                <?php if ($nav_role === 'user'): ?>
                    <a class="lux-nav__link" href="/account">Mon espace</a>
                <?php endif; ?>
                <?php if ($nav_role === 'admin'): ?>
                    <a class="lux-nav__link" href="/admin">Administration</a>
                    <a class="lux-nav__link" href="/admin/analytics">Marché & tendances</a>
                <?php endif; ?>
                <?php if ($nav_role === 'agent'): ?>
                    <a class="lux-nav__link" href="/agent">Espace agent</a>
                    <a class="lux-nav__link" href="/agent/biens">Mes biens</a>
                    <a class="lux-nav__link" href="/agent/demandes">Demandes</a>
                    <a class="lux-nav__link" href="/agent/dossiers">Dossiers</a>
                <?php endif; ?>
                <a class="lux-nav__link" href="/logout">Déconnexion</a>
            <?php else: ?>
                <a class="lux-nav__link" href="/login">Connexion</a>
                <a class="lux-btn lux-btn--secondary" href="/register"><span>S'inscrire</span></a>
            <?php endif; ?>
        </nav>
    </div>
</header>
