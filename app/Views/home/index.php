<!-- Page d'accueil avec message de bienvenue et liste des produits -->
<div style="max-width: 1400px; margin: 0 auto; padding: 30px;">
    <!-- Message de bienvenue -->
    <div style="text-align: center; margin-bottom: 50px; padding: 50px 30px; background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); border-radius: 20px; color: white; box-shadow: 0 10px 40px rgba(0,0,0,0.2); position: relative; overflow: hidden; border-top: 4px solid #dc3545;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(220, 53, 69, 0.1); border-radius: 50%; filter: blur(40px);"></div>
        <div style="position: absolute; bottom: -50px; left: -50px; width: 200px; height: 200px; background: rgba(220, 53, 69, 0.1); border-radius: 50%; filter: blur(40px);"></div>
        <div style="position: relative; z-index: 1;">
            <h1 style="margin: 0 0 15px 0; font-size: 48px; font-weight: 800; letter-spacing: -1px; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                Bienvenue sur <span style="color: #dc3545;">HolyGoods</span> ⚡
            </h1>
            <p style="margin: 0; font-size: 20px; opacity: 0.95; font-weight: 300;">
                Découvrez notre sélection exclusive de produits premium
            </p>
        </div>
    </div>
    
    <!-- Liste des produits -->
    <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 60px 40px; background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <div style="font-size: 64px; margin-bottom: 20px;">📦</div>
            <p style="color: #666; font-size: 20px; font-weight: 500;">Aucun produit disponible pour le moment.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px; align-items: stretch;">
            <?php foreach ($products as $product): ?>
                <a href="/products/show?id=<?= htmlspecialchars($product['id']) ?>" 
                   style="text-decoration: none; color: inherit; display: flex; height: 100%;">
                    <div style="border: none; border-radius: 16px; padding: 0; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.3s ease; cursor: pointer; display: flex; flex-direction: column; width: 100%; overflow: hidden;"
                         onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 40px rgba(102, 126, 234, 0.2)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
                        <!-- Image du produit -->
                        <?php if (!empty($product['image_url'])): ?>
                            <div style="margin: 0; text-align: center; height: 240px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); flex-shrink: 0; position: relative;">
                                <img 
                                    src="<?= htmlspecialchars($product['image_url']) ?>" 
                                    alt="<?= htmlspecialchars($product['nom']) ?>" 
                                    style="max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.3s ease;"
                                    onerror="this.style.display='none'"
                                    onmouseover="this.style.transform='scale(1.05)'"
                                    onmouseout="this.style.transform='scale(1)'"
                                >
                            </div>
                        <?php else: ?>
                            <div style="margin: 0; text-align: center; height: 240px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); flex-shrink: 0;">
                                <span style="color: #999; font-size: 16px; font-weight: 500;">Aucune image</span>
                            </div>
                        <?php endif; ?>
                        
                        <div style="padding: 20px; display: flex; flex-direction: column; flex: 1;">
                            <!-- Nom du produit -->
                            <h3 style="margin: 0 0 12px 0; color: #2d3748; font-size: 20px; font-weight: 700; min-height: 60px; display: flex; align-items: center; line-height: 1.3;">
                                <?= htmlspecialchars($product['nom']) ?>
                            </h3>
                            
                        <?php if (!empty($product['categorie_nom'])): ?>
                            <div style="margin-bottom: 12px; font-size: 13px; color: #718096; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">
                                📁 <?= htmlspecialchars($product['categorie_nom']) ?>
                            </div>
                        <?php endif; ?>
                            
                            <!-- Prix et stock -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 20px; border-top: 2px solid #f7fafc;">
                            <div style="font-size: 26px; font-weight: 800; color: #dc3545; letter-spacing: -0.5px;">
                                <?= number_format((float)$product['prix'], 2, ',', ' ') ?> €
                            </div>
                            <div style="font-size: 13px; color: <?= $product['stock'] > 0 ? '#2d3748' : '#dc3545' ?>; font-weight: 600; padding: 6px 12px; background: <?= $product['stock'] > 0 ? 'rgba(45, 55, 72, 0.1)' : 'rgba(220, 53, 69, 0.1)' ?>; border-radius: 20px;">
                                <?php if ($product['stock'] > 0): ?>
                                    ✅ En stock
                                <?php else: ?>
                                    ❌ Épuisé
                                <?php endif; ?>
                            </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

