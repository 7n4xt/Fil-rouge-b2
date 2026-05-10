<?php
$page_title = ($data['estate_address'] ?? '') . ' — Ymmo';
$main_photo = !empty($data['photos'][0]['url_path'])
    ? $data['photos'][0]['url_path']
    : 'pictures/house/default.jpg';
require __DIR__ . '/partials/layout_start.php';
require __DIR__ . '/partials/site_header.php';
?>
<main class="lux-main">
    <div class="lux-container">
        <a class="lux-back" href="/properties">Retour à la collection</a>

        <?php if (!empty($data['flash_success'])): ?>
            <p class="lux-flash"><?= htmlspecialchars($data['flash_success'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <?php if (!empty($data['flash_error'])): ?>
            <ul class="lux-errors"><li><?= htmlspecialchars($data['flash_error'], ENT_QUOTES, 'UTF-8') ?></li></ul>
        <?php endif; ?>

        <div class="lux-gallery">
            <div class="lux-gallery__main">
                <img id="mainImage" src="/<?= htmlspecialchars($main_photo, ENT_QUOTES, 'UTF-8') ?>"
                    alt="<?= htmlspecialchars($data['estate_address'], ENT_QUOTES, 'UTF-8') ?>"
                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect fill=%22%23EBE5DE%22 width=%22400%22 height=%22300%22/%3E%3C/svg%3E'">
            </div>
            <?php if (!empty($data['photos'])): ?>
                <div class="lux-thumbs">
                    <?php foreach (array_slice($data['photos'], 0, 4) as $photo): ?>
                        <button type="button" class="lux-thumb" aria-label="Afficher cette photo"
                            onclick="document.getElementById('mainImage').src='/<?= htmlspecialchars($photo['url_path'], ENT_QUOTES, 'UTF-8') ?>'">
                            <img src="/<?= htmlspecialchars($photo['url_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="lux-detail-grid">
            <div class="lux-panel">
                <p class="lux-overline"><?= htmlspecialchars($data['estate_type'], ENT_QUOTES, 'UTF-8') ?></p>
                <h1 class="lux-title"><?= htmlspecialchars($data['estate_address'], ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="lux-lede"><?= htmlspecialchars($data['estate_country'], ENT_QUOTES, 'UTF-8') ?></p>

                <div class="lux-features">
                    <div>
                        <span class="lux-feature-label">Surface</span>
                        <span class="lux-feature-value"><?= (int) $data['surface'] ?> m²</span>
                    </div>
                    <div>
                        <span class="lux-feature-label">Pièces</span>
                        <span class="lux-feature-value"><?= (int) $data['rooms_count'] ?></span>
                    </div>
                    <div>
                        <span class="lux-feature-label">Chambres</span>
                        <span class="lux-feature-value"><?= (int) $data['bedrooms_count'] ?></span>
                    </div>
                    <div>
                        <span class="lux-feature-label">Classe énergie</span>
                        <span class="lux-feature-value"><?= $data['energy_class'] ? htmlspecialchars($data['energy_class'], ENT_QUOTES, 'UTF-8') : '—' ?></span>
                    </div>
                    <div>
                        <span class="lux-feature-label">Statut</span>
                        <span class="lux-feature-value"><?= htmlspecialchars($data['estate_status'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>

                <?php if (!empty($data['description'])): ?>
                    <div class="lux-prose">
                        <h2 class="lux-overline">Description</h2>
                        <p class="lux-dropcap"><?= nl2br(htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['files'])): ?>
                    <div class="lux-panel">
                        <h2 class="lux-overline">Documents</h2>
                        <ul class="lux-file-list">
                            <?php foreach ($data['files'] as $file): ?>
                                <li>
                                    <a class="lux-link" href="/<?= htmlspecialchars($file['file_url'], ENT_QUOTES, 'UTF-8') ?>" download>
                                        <?= htmlspecialchars($file['file_name'], ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <aside>
                <div class="lux-side-card">
                    <p class="lux-price-xl"><?= number_format((float) $data['price'], 0, ',', ' ') ?> €</p>
                </div>

                <?php if (!empty($data['agence_name'])): ?>
                    <div class="lux-side-card">
                        <h3>Agence</h3>
                        <p><strong><?= htmlspecialchars($data['agence_name'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                        <p class="lux-lede lux-lede--flush"><?= htmlspecialchars($data['agence_address'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['first_name'])): ?>
                    <div class="lux-side-card">
                        <h3>Agent</h3>
                        <p><strong><?= htmlspecialchars($data['first_name'] . ' ' . $data['last_name'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                        <div class="lux-contact-actions">
                            <?php if (!empty($data['phone_number'])): ?>
                                <a class="lux-btn lux-btn--primary" href="tel:<?= htmlspecialchars($data['phone_number'], ENT_QUOTES, 'UTF-8') ?>"><span><?= htmlspecialchars($data['phone_number'], ENT_QUOTES, 'UTF-8') ?></span></a>
                            <?php endif; ?>
                            <?php if (!empty($data['mail'])): ?>
                                <a class="lux-btn lux-btn--secondary" href="mailto:<?= htmlspecialchars($data['mail'], ENT_QUOTES, 'UTF-8') ?>"><span>Écrire</span></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['is_client'])): ?>
                    <div class="lux-side-card">
                        <h3>Demande d’infos ou visite</h3>
                        <p class="lux-lede lux-lede--flush">Votre message est transmis à l’agence et visible dans <a class="lux-link" href="/account">Mon espace</a>.</p>
                        <form class="lux-form lux-form--compact" method="post" action="/properties/<?= (int) $data['estate_id'] ?>">
                            <?php require __DIR__ . '/partials/csrf_field.php'; ?>
                            <div class="lux-field">
                                <label for="request_kind">Type</label>
                                <select class="lux-input" name="request_kind" id="request_kind">
                                    <option value="information">Demande d’information</option>
                                    <option value="visite">Demande de visite</option>
                                </select>
                            </div>
                            <div class="lux-field">
                                <label for="message">Message</label>
                                <textarea class="lux-input" name="message" id="message" rows="3" placeholder="Votre question ou précisions"></textarea>
                            </div>
                            <div class="lux-field">
                                <label for="preferred_visit_at">Créneau souhaité (visite)</label>
                                <input class="lux-input" type="datetime-local" name="preferred_visit_at" id="preferred_visit_at">
                            </div>
                            <button class="lux-btn lux-btn--primary" type="submit"><span>Envoyer</span></button>
                        </form>
                    </div>
                <?php elseif (in_array($_SESSION['role'] ?? '', ['admin', 'agent'], true)): ?>
                    <div class="lux-side-card">
                        <h3>Demandes clients</h3>
                        <p class="lux-lede lux-lede--flush">Les formulaires clients sont réservés aux comptes « client ». Gérez les dossiers depuis votre espace pro.</p>
                    </div>
                <?php elseif (empty($_SESSION['user_id'])): ?>
                    <div class="lux-side-card">
                        <h3>Demande d’infos ou visite</h3>
                        <p class="lux-lede lux-lede--flush"><a class="lux-link" href="/login">Connectez-vous</a> avec un compte client pour envoyer une demande structurée. Les coordonnées ci-dessus restent disponibles.</p>
                    </div>
                <?php endif; ?>
            </aside>
        </div>

        <?php if (!empty($data['similar'])): ?>
            <section class="lux-panel lux-section-spaced">
                <h2 class="lux-title lux-title-sub">Biens <em>voisins</em></h2>
                <div class="lux-card-grid lux-card-grid--tight">
                    <?php foreach ($data['similar'] as $similar): ?>
                        <article class="lux-card" role="link" tabindex="0"
                            onclick="window.location.href='/properties/<?= (int) $similar['estate_id'] ?>'"
                            onkeydown="if(event.key==='Enter')window.location.href='/properties/<?= (int) $similar['estate_id'] ?>'">
                            <div class="lux-card__media">
                                <img src="/<?= htmlspecialchars($similar['main_photo'], ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars($similar['estate_address'], ENT_QUOTES, 'UTF-8') ?>"
                                    loading="lazy"
                                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect fill=%22%23EBE5DE%22 width=%22400%22 height=%22300%22/%3E%3C/svg%3E'">
                            </div>
                            <h3 class="lux-card__price"><?= number_format((float) $similar['price'], 0, ',', ' ') ?> €</h3>
                            <p class="lux-card__address"><?= htmlspecialchars(substr($similar['estate_address'], 0, 50), ENT_QUOTES, 'UTF-8') ?>…</p>
                            <div class="lux-card__meta">
                                <div><strong><?= (int) $similar['surface'] ?></strong> m²</div>
                                <div><strong><?= (int) $similar['rooms_count'] ?></strong> pièces</div>
                                <div><strong><?= (int) $similar['bedrooms_count'] ?></strong> ch.</div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>
<?php require __DIR__ . '/partials/site_footer.php'; ?>
<?php require __DIR__ . '/partials/layout_end.php'; ?>
