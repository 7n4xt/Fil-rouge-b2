<?php ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['estate_address']); ?> - Immobilier</title>
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
            padding: 1.5rem 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .back-btn {
            display: inline-block;
            color: white;
            text-decoration: none;
            margin-bottom: 1rem;
            transition: opacity 0.3s;
        }
        .back-btn:hover {
            opacity: 0.8;
        }
        .gallery {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .main-image {
            width: 100%;
            height: 400px;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .thumbnails {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            padding: 1rem;
            background: white;
        }
        .thumbnail {
            width: 100%;
            height: 100px;
            background: #e0e0e0;
            border-radius: 4px;
            cursor: pointer;
            overflow: hidden;
            border: 2px solid transparent;
            transition: border-color 0.3s;
        }
        .thumbnail:hover {
            border-color: #667eea;
        }
        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .content-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .main-content {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .aside {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .price {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .address {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .location {
            color: #999;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin: 2rem 0;
            padding: 1.5rem 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }
        .feature {
            display: flex;
            flex-direction: column;
        }
        .feature-label {
            color: #999;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
        }
        .feature-value {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }
        .description {
            margin: 2rem 0;
            line-height: 1.6;
        }
        .description h3 {
            margin-bottom: 1rem;
            color: #667eea;
        }
        .info-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .info-card h3 {
            margin-bottom: 1rem;
            color: #667eea;
            font-size: 1.1rem;
        }
        .info-card p {
            margin-bottom: 0.5rem;
            color: #666;
        }
        .info-card strong {
            color: #333;
        }
        .agent-contact {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .contact-btn {
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }
        .btn-call {
            background: #667eea;
            color: white;
        }
        .btn-call:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }
        .btn-email {
            background: #f0f0f0;
            color: #333;
        }
        .btn-email:hover {
            background: #e0e0e0;
        }
        .files-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }
        .files-section h3 {
            margin-bottom: 1rem;
            color: #667eea;
        }
        .file-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        .file-item {
            background: #f9f9f9;
            padding: 1rem;
            border-radius: 4px;
            border: 1px solid #eee;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .file-item:hover {
            background: #f0f0f0;
            border-color: #667eea;
        }
        .file-item a {
            text-decoration: none;
            color: #667eea;
            font-weight: 600;
        }
        .similar-properties {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }
        .similar-properties h3 {
            margin-bottom: 1.5rem;
            color: #667eea;
            font-size: 1.5rem;
        }
        .similar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        .similar-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s;
        }
        .similar-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        .similar-image {
            width: 100%;
            height: 150px;
            background: #e0e0e0;
            overflow: hidden;
        }
        .similar-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .similar-info {
            padding: 1rem;
        }
        .similar-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .similar-address {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .similar-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #999;
        }
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }
        @media (max-width: 768px) {
            .gallery {
                grid-template-columns: 1fr;
            }
            .content-wrapper {
                grid-template-columns: 1fr;
            }
            .features {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <a href="/properties" class="back-btn">← Retour à la liste</a>
        </div>
    </header>

    <div class="container">
        <!-- Gallery -->
        <div class="gallery">
            <div class="main-image">
                <?php 
                $mainPhoto = !empty($data['photos'][0]['url_path']) 
                    ? $data['photos'][0]['url_path'] 
                    : 'pictures/house/default.jpg';
                ?>
                <img id="mainImage" src="/<?php echo htmlspecialchars($mainPhoto); ?>" 
                    alt="<?php echo htmlspecialchars($data['estate_address']); ?>"
                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect fill=%22%23ddd%22 width=%22400%22 height=%22300%22/%3E%3C/svg%3E'">
            </div>
            <?php if (!empty($data['photos'])): ?>
                <div class="thumbnails">
                    <?php foreach (array_slice($data['photos'], 0, 4) as $photo): ?>
                        <div class="thumbnail" onclick="document.getElementById('mainImage').src='/<?php echo htmlspecialchars($photo['url_path']); ?>'">
                            <img src="/<?php echo htmlspecialchars($photo['url_path']); ?>" alt="Photo">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="content-wrapper">
            <!-- Main Content -->
            <div class="main-content">
                <h1 class="address">
                    <?php echo htmlspecialchars($data['estate_address']); ?>
                </h1>
                <p class="location">
                    📍 <?php echo htmlspecialchars($data['estate_country']); ?>
                </p>

                <div class="features">
                    <div class="feature">
                        <span class="feature-label">Surface</span>
                        <span class="feature-value"><?php echo $data['surface']; ?> m²</span>
                    </div>
                    <div class="feature">
                        <span class="feature-label">Pièces</span>
                        <span class="feature-value"><?php echo $data['rooms_count']; ?></span>
                    </div>
                    <div class="feature">
                        <span class="feature-label">Chambres</span>
                        <span class="feature-value"><?php echo $data['bedrooms_count']; ?></span>
                    </div>
                    <div class="feature">
                        <span class="feature-label">Type</span>
                        <span class="feature-value"><?php echo htmlspecialchars($data['estate_type']); ?></span>
                    </div>
                    <div class="feature">
                        <span class="feature-label">Classe énergétique</span>
                        <span class="feature-value"><?php echo $data['energy_class'] ?: 'N/A'; ?></span>
                    </div>
                    <div class="feature">
                        <span class="feature-label">Statut</span>
                        <span class="feature-value"><?php echo htmlspecialchars($data['estate_status']); ?></span>
                    </div>
                </div>

                <?php if (!empty($data['description'])): ?>
                    <div class="description">
                        <h3>Description</h3>
                        <p><?php echo nl2br(htmlspecialchars($data['description'])); ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['files'])): ?>
                    <div class="files-section">
                        <h3>Documents disponibles</h3>
                        <div class="file-list">
                            <?php foreach ($data['files'] as $file): ?>
                                <div class="file-item">
                                    <a href="/<?php echo htmlspecialchars($file['file_url']); ?>" download>
                                        📄 <?php echo htmlspecialchars($file['file_name']); ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside>
                <!-- Price Card -->
                <div class="info-card">
                    <div class="price">
                        <?php echo number_format($data['price'], 0, ',', ' '); ?> €
                    </div>
                </div>

                <!-- Agency Info -->
                <?php if (!empty($data['agence_name'])): ?>
                    <div class="info-card">
                        <h3>Agence</h3>
                        <p><strong><?php echo htmlspecialchars($data['agence_name']); ?></strong></p>
                        <p><?php echo htmlspecialchars($data['agence_address']); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Agent Contact -->
                <?php if (!empty($data['first_name'])): ?>
                    <div class="info-card">
                        <h3>Agent immobilier</h3>
                        <p><strong><?php echo htmlspecialchars($data['first_name'] . ' ' . $data['last_name']); ?></strong></p>
                        <div class="agent-contact">
                            <?php if (!empty($data['phone_number'])): ?>
                                <a href="tel:<?php echo htmlspecialchars($data['phone_number']); ?>" class="contact-btn btn-call">
                                    📞 <?php echo htmlspecialchars($data['phone_number']); ?>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($data['mail'])): ?>
                                <a href="mailto:<?php echo htmlspecialchars($data['mail']); ?>" class="contact-btn btn-email">
                                    ✉️ Envoyer un email
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </aside>
        </div>

        <!-- Similar Properties -->
        <?php if (!empty($data['similar'])): ?>
            <div class="similar-properties">
                <h3>Propriétés similaires</h3>
                <div class="similar-grid">
                    <?php foreach ($data['similar'] as $similar): ?>
                        <div class="similar-card" onclick="window.location.href='/properties/<?php echo $similar['estate_id']; ?>'">
                            <div class="similar-image">
                                <img src="/<?php echo htmlspecialchars($similar['main_photo']); ?>" 
                                    alt="<?php echo htmlspecialchars($similar['estate_address']); ?>"
                                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect fill=%22%23ddd%22 width=%22400%22 height=%22300%22/%3E%3C/svg%3E'">
                            </div>
                            <div class="similar-info">
                                <div class="similar-price">
                                    <?php echo number_format($similar['price'], 0, ',', ' '); ?> €
                                </div>
                                <div class="similar-address">
                                    <?php echo htmlspecialchars(substr($similar['estate_address'], 0, 50)); ?>...
                                </div>
                                <div class="similar-features">
                                    <span><?php echo $similar['surface']; ?> m²</span>
                                    <span><?php echo $similar['rooms_count']; ?> pièces</span>
                                    <span><?php echo $similar['bedrooms_count']; ?> chamb.</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2026 Immobilier. Tous droits réservés.</p>
    </footer>
</body>
</html>