<?php ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Propriétés - Immobilier</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .filters {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        .filter-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #667eea;
        }
        .filter-group input,
        .filter-group select {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .filter-buttons {
            display: flex;
            gap: 1rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }
        .btn-search {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .btn-reset {
            background: #f0f0f0;
            color: #333;
        }
        .btn-reset:hover {
            background: #e0e0e0;
        }
        .error-message {
            background: #fee;
            color: #c33;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 2rem;
            border-left: 4px solid #c33;
        }
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .property-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
            cursor: pointer;
        }
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        .property-image {
            width: 100%;
            height: 200px;
            background: #e0e0e0;
            overflow: hidden;
            position: relative;
        }
        .property-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .property-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .property-info {
            padding: 1.5rem;
        }
        .property-price {
            font-size: 1.75rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .property-address {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .property-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding: 1rem 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }
        .feature {
            text-align: center;
            font-size: 0.85rem;
        }
        .feature-value {
            font-weight: 600;
            color: #333;
        }
        .feature-label {
            color: #999;
            font-size: 0.75rem;
        }
        .btn-details {
            width: 100%;
            padding: 0.75rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }
        .btn-details:hover {
            background: #764ba2;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 3rem;
            flex-wrap: wrap;
        }
        .pagination a,
        .pagination span {
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }
        .pagination a:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .pagination .current {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        .no-results {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .no-results h2 {
            color: #999;
            margin-bottom: 1rem;
        }
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>🏘️ Nos Propriétés</h1>
            <p>Découvrez nos biens immobiliers de qualité</p>
        </div>
    </header>

    <div class="container">
        <?php if (!empty($data['error'])): ?>
            <div class="error-message">
                ⚠️ Une erreur s'est produite. Veuillez réessayer plus tard.
            </div>
        <?php endif; ?>

        <!-- Filters Section -->
        <div class="filters">
            <form method="GET" action="/properties">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="type">Type de propriété</label>
                        <select name="type" id="type">
                            <option value="">Tous les types</option>
                            <?php foreach ($data['types'] as $type): ?>
                                <option value="<?php echo htmlspecialchars($type); ?>" 
                                    <?php echo ($data['currentFilters']['type'] ?? '') === $type ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="country">Pays</label>
                        <select name="country" id="country">
                            <option value="">Tous les pays</option>
                            <?php foreach ($data['countries'] as $country): ?>
                                <option value="<?php echo htmlspecialchars($country); ?>" 
                                    <?php echo ($data['currentFilters']['country'] ?? '') === $country ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($country); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="minPrice">Prix minimum (€)</label>
                        <input type="number" name="minPrice" id="minPrice" 
                            value="<?php echo htmlspecialchars($data['currentFilters']['minPrice'] ?? ''); ?>"
                            min="0" max="<?php echo $data['priceRange']['maxPrice']; ?>">
                    </div>

                    <div class="filter-group">
                        <label for="maxPrice">Prix maximum (€)</label>
                        <input type="number" name="maxPrice" id="maxPrice" 
                            value="<?php echo htmlspecialchars($data['currentFilters']['maxPrice'] ?? ''); ?>"
                            min="0" max="<?php echo $data['priceRange']['maxPrice']; ?>">
                    </div>

                    <div class="filter-group">
                        <label for="minRooms">Nombre minimum de pièces</label>
                        <input type="number" name="minRooms" id="minRooms" 
                            value="<?php echo htmlspecialchars($data['currentFilters']['minRooms'] ?? ''); ?>"
                            min="0" max="20">
                    </div>
                </div>

                <div class="filter-buttons">
                    <button type="submit" class="btn btn-search">🔍 Rechercher</button>
                    <a href="/properties" class="btn btn-reset">Réinitialiser</a>
                </div>
            </form>
        </div>

        <!-- Properties Display -->
        <?php if (empty($data['properties'])): ?>
            <div class="no-results">
                <h2>Aucune propriété trouvée</h2>
                <p>Essayez de modifier vos critères de recherche.</p>
            </div>
        <?php else: ?>
            <div class="properties-grid">
                <?php foreach ($data['properties'] as $property): ?>
                    <div class="property-card" onclick="window.location.href='/properties/<?php echo $property['estate_id']; ?>'">
                        <div class="property-image">
                            <img src="/<?php echo htmlspecialchars($property['main_photo']); ?>" 
                                alt="<?php echo htmlspecialchars($property['estate_address']); ?>"
                                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect fill=%22%23ddd%22 width=%22400%22 height=%22300%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%23999%22 font-size=%2224%22%3EImage non disponible%3C/text%3E%3C/svg%3E'">
                            <div class="property-badge"><?php echo htmlspecialchars($property['estate_type']); ?></div>
                        </div>
                        <div class="property-info">
                            <div class="property-price">
                                <?php echo number_format($property['price'], 0, ',', ' '); ?> €
                            </div>
                            <div class="property-address">
                                📍 <?php echo htmlspecialchars($property['estate_address']); ?>
                            </div>
                            <div class="property-features">
                                <div class="feature">
                                    <div class="feature-value"><?php echo $property['surface']; ?></div>
                                    <div class="feature-label">m²</div>
                                </div>
                                <div class="feature">
                                    <div class="feature-value"><?php echo $property['rooms_count']; ?></div>
                                    <div class="feature-label">Pièces</div>
                                </div>
                                <div class="feature">
                                    <div class="feature-value"><?php echo $property['bedrooms_count']; ?></div>
                                    <div class="feature-label">Chambres</div>
                                </div>
                            </div>
                            <button class="btn-details">Voir les détails →</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($data['pages'] > 1): ?>
                <div class="pagination">
                    <?php if ($data['currentPage'] > 1): ?>
                        <a href="?page=1<?php echo http_build_query($data['currentFilters'] ? '&' . http_build_query($data['currentFilters']) : ''); ?>">« Première</a>
                        <a href="?page=<?php echo $data['currentPage'] - 1; ?><?php echo $data['currentFilters'] ? '&' . http_build_query($data['currentFilters']) : ''; ?>">‹ Précédente</a>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $data['currentPage'] - 2);
                    $end = min($data['pages'], $data['currentPage'] + 2);
                    for ($i = $start; $i <= $end; $i++):
                    ?>
                        <?php if ($i === $data['currentPage']): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?><?php echo $data['currentFilters'] ? '&' . http_build_query($data['currentFilters']) : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($data['currentPage'] < $data['pages']): ?>
                        <a href="?page=<?php echo $data['currentPage'] + 1; ?><?php echo $data['currentFilters'] ? '&' . http_build_query($data['currentFilters']) : ''; ?>">Suivante ›</a>
                        <a href="?page=<?php echo $data['pages']; ?><?php echo $data['currentFilters'] ? '&' . http_build_query($data['currentFilters']) : ''; ?>">Dernière »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div style="text-align: center; margin: 2rem 0; color: #999;">
                Total: <?php echo $data['total']; ?> propriétés | Page <?php echo $data['currentPage']; ?> sur <?php echo $data['pages']; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2026 Immobilier. Tous droits réservés.</p>
    </footer>
</body>
</html>