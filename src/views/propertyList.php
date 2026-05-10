<?php
$page_title = 'Collection — Ymmo';
$filter_query = !empty($data['currentFilters']) ? '&' . http_build_query($data['currentFilters']) : '';
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container">
        <p class="lux-overline">Immobilier</p>
        <h1 class="lux-title">Notre <em>collection</em></h1>
        <p class="lux-lede">Découvrez une sélection de biens présentée avec le soin d’une maison éditoriale. Filtrez par type, lieu et budget.</p>

        <?php if (!empty($data['error'])): ?>
            <div class="lux-errors" role="alert">Une erreur s'est produite. Veuillez réessayer plus tard.</div>
        <?php endif; ?>

        <section class="lux-filters" aria-label="Filtres">
            <form method="GET" action="/properties">
                <div class="lux-filter-grid">
                    <div class="lux-field">
                        <label for="type">Type</label>
                        <select name="type" id="type">
                            <option value="">Tous les types</option>
                            <?php foreach ($data['types'] as $type): ?>
                                <option value="<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>"
                                    <?= ($data['currentFilters']['type'] ?? '') === $type ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="lux-field">
                        <label for="country">Pays</label>
                        <select name="country" id="country">
                            <option value="">Tous les pays</option>
                            <?php foreach ($data['countries'] as $country): ?>
                                <option value="<?= htmlspecialchars($country, ENT_QUOTES, 'UTF-8') ?>"
                                    <?= ($data['currentFilters']['country'] ?? '') === $country ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($country, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="lux-field">
                        <label for="minPrice">Prix min (€)</label>
                        <input class="lux-input" type="number" name="minPrice" id="minPrice"
                            value="<?= htmlspecialchars($data['currentFilters']['minPrice'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            min="0" max="<?= (int) $data['priceRange']['maxPrice'] ?>">
                    </div>
                    <div class="lux-field">
                        <label for="maxPrice">Prix max (€)</label>
                        <input class="lux-input" type="number" name="maxPrice" id="maxPrice"
                            value="<?= htmlspecialchars($data['currentFilters']['maxPrice'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            min="0" max="<?= (int) $data['priceRange']['maxPrice'] ?>">
                    </div>
                    <div class="lux-field">
                        <label for="minRooms">Pièces min.</label>
                        <input class="lux-input" type="number" name="minRooms" id="minRooms"
                            value="<?= htmlspecialchars($data['currentFilters']['minRooms'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            min="0" max="20">
                    </div>
                </div>
                <div class="lux-btn-row">
                    <button class="lux-btn lux-btn--primary" type="submit"><span>Rechercher</span></button>
                    <a class="lux-link" href="/properties">Réinitialiser</a>
                </div>
            </form>
        </section>

        <?php if (empty($data['properties'])): ?>
            <div class="lux-empty">
                <h2>Aucun bien trouvé</h2>
                <p class="lux-lede lux-lede--flush">Affinez ou effacez les filtres pour élargir la sélection.</p>
            </div>
        <?php else: ?>
            <div class="lux-card-grid">
                <?php foreach ($data['properties'] as $property): ?>
                    <article class="lux-card" role="link" tabindex="0"
                        onclick="window.location.href='/properties/<?= (int) $property['estate_id'] ?>'"
                        onkeydown="if(event.key==='Enter')window.location.href='/properties/<?= (int) $property['estate_id'] ?>'">
                        <div class="lux-card__media">
                            <img src="/<?= htmlspecialchars($property['main_photo'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($property['estate_address'], ENT_QUOTES, 'UTF-8') ?>"
                                loading="lazy"
                                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect fill=%22%23EBE5DE%22 width=%22400%22 height=%22300%22/%3E%3C/svg%3E'">
                            <span class="lux-card__badge"><?= htmlspecialchars($property['estate_type'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <h2 class="lux-card__price"><?= number_format((float) $property['price'], 0, ',', ' ') ?> €</h2>
                        <p class="lux-card__address"><?= htmlspecialchars($property['estate_address'], ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="lux-card__meta">
                            <div><strong><?= (int) $property['surface'] ?></strong> m²</div>
                            <div><strong><?= (int) $property['rooms_count'] ?></strong> pièces</div>
                            <div><strong><?= (int) $property['bedrooms_count'] ?></strong> ch.</div>
                        </div>
                        <span class="lux-link">Voir le détail</span>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($data['pages'] > 1): ?>
                <nav class="lux-pagination" aria-label="Pagination">
                    <?php if ($data['currentPage'] > 1): ?>
                        <a href="?page=1<?= htmlspecialchars($filter_query, ENT_QUOTES, 'UTF-8') ?>">«</a>
                        <a href="?page=<?= (int) $data['currentPage'] - 1 ?><?= htmlspecialchars($filter_query, ENT_QUOTES, 'UTF-8') ?>">‹</a>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $data['currentPage'] - 2);
                    $end = min($data['pages'], $data['currentPage'] + 2);
                    for ($i = $start; $i <= $end; $i++):
                    ?>
                        <?php if ($i === $data['currentPage']): ?>
                            <span class="is-current" aria-current="page"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?><?= htmlspecialchars($filter_query, ENT_QUOTES, 'UTF-8') ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($data['currentPage'] < $data['pages']): ?>
                        <a href="?page=<?= (int) $data['currentPage'] + 1 ?><?= htmlspecialchars($filter_query, ENT_QUOTES, 'UTF-8') ?>">›</a>
                        <a href="?page=<?= (int) $data['pages'] ?><?= htmlspecialchars($filter_query, ENT_QUOTES, 'UTF-8') ?>">»</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>

            <p class="lux-meta-bar"><?= (int) $data['total'] ?> biens — page <?= (int) $data['currentPage'] ?> / <?= (int) $data['pages'] ?></p>
        <?php endif; ?>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
