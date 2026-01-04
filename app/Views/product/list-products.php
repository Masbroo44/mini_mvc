<!-- Liste des produits -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
?>
<div style="max-width: 1400px; margin: 0 auto; padding: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <h2 style="font-size: 32px; font-weight: 800; color: #2d3748; letter-spacing: -0.5px;">Liste des produits</h2>
        <?php if ($isAdmin): ?>
            <a href="/products/create" 
               style="padding: 12px 24px; background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); color: white; text-decoration: none; border-radius: 12px; display: inline-block; font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease; border: 2px solid #dc3545;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220, 53, 69, 0.4)'; this.style.borderColor='#dc3545'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)'; this.style.borderColor='#dc3545'">
                Ajouter un produit
            </a>
        <?php endif; ?>
    </div>
    
    <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 40px; background-color: #f8f9fa; border-radius: 4px;">
            <p style="color: #666; font-size: 18px;">Aucun produit disponible.</p>
            <a href="/products/create" style="color: #007bff; text-decoration: none;">Créer le premier produit</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; align-items: stretch;">
            <?php foreach ($products as $product): ?>
                <div style="border: none; border-radius: 16px; padding: 0; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.08); display: flex; flex-direction: column; height: 100%; overflow: hidden; transition: all 0.3s ease;"
                     onmouseover="this.style.boxShadow='0 8px 30px rgba(102, 126, 234, 0.15)'"
                     onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.08)'">
                    <!-- Image du produit -->
                    <?php if (!empty($product['image_url'])): ?>
                        <div style="margin: 0; text-align: center; height: 240px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); flex-shrink: 0;">
                            <img 
                                src="<?= htmlspecialchars($product['image_url']) ?>" 
                                alt="<?= htmlspecialchars($product['nom']) ?>" 
                                style="max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.3s ease;"
                                onerror="this.style.display='none'"
                            >
                        </div>
                    <?php else: ?>
                        <div style="margin: 0; text-align: center; height: 240px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); flex-shrink: 0;">
                            <span style="color: #999; font-size: 16px; font-weight: 500;">Aucune image</span>
                        </div>
                    <?php endif; ?>
                    
                    <div style="padding: 20px; display: flex; flex-direction: column; flex: 1;">
                        <!-- Informations du produit -->
                        <h3 style="margin: 0 0 12px 0; color: #2d3748; font-size: 20px; font-weight: 700; min-height: 60px; display: flex; align-items: center; line-height: 1.3;">
                            <?= htmlspecialchars($product['nom']) ?>
                        </h3>
                        
                        <?php if (!empty($product['description'])): ?>
                            <p style="margin: 0 0 15px 0; color: #718096; font-size: 14px; line-height: 1.6; 
                                      display: -webkit-box; 
                                      -webkit-line-clamp: 3; 
                                      -webkit-box-orient: vertical; 
                                      overflow: hidden; 
                                      text-overflow: ellipsis;
                                      max-height: 4.5em;
                                      flex-shrink: 0;">
                                <?= htmlspecialchars($product['description']) ?>
                            </p>
                        <?php endif; ?>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 20px; border-top: 2px solid #f7fafc;">
                            <div>
                                <div style="font-size: 26px; font-weight: 800; color: #dc3545; letter-spacing: -0.5px;">
                                    <?= number_format((float)$product['prix'], 2, ',', ' ') ?> €
                                </div>
                                <div style="font-size: 13px; color: #718096; margin-top: 5px; font-weight: 500;">
                                    Stock: <?= htmlspecialchars($product['stock']) ?>
                                </div>
                                <?php if (!empty($product['categorie_nom'])): ?>
                                    <div style="font-size: 12px; color: #718096; margin-top: 5px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <?= htmlspecialchars($product['categorie_nom']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 10px; flex-shrink: 0;">
                            <a href="/products/show?id=<?= htmlspecialchars($product['id']) ?>" 
                               style="width: 100%; padding: 12px 20px; background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); color: white; text-decoration: none; border-radius: 12px; text-align: center; font-size: 14px; font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease; border: 2px solid #dc3545;"
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220, 53, 69, 0.4)'; this.style.borderColor='#dc3545'"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)'; this.style.borderColor='#dc3545'">
                                Voir détails
                            </a>
                            
                            <?php if ($isAdmin): ?>
                                <div style="display: flex; gap: 10px;">
                                    <a href="/products/edit?id=<?= htmlspecialchars($product['id']) ?>" 
                                       style="flex: 1; padding: 12px 20px; background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); color: white; text-decoration: none; border-radius: 12px; text-align: center; font-size: 14px; font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease; border: 2px solid #dc3545;"
                                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220, 53, 69, 0.4)'; this.style.borderColor='#dc3545'"
                                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)'; this.style.borderColor='#dc3545'">
                                        Modifier
                                    </a>
                                    <form method="POST" action="/products/delete" style="flex: 1; margin: 0;" onsubmit="return confirm('Supprimer ce produit ? Cette action est définitive.');">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                                        <button type="submit"
                                                style="width: 100%; padding: 12px 20px; background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); color: white; border: 2px solid #dc3545; border-radius: 12px; cursor: pointer; font-size: 14px; font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease;"
                                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220, 53, 69, 0.4)'; this.style.borderColor='#dc3545'"
                                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)'; this.style.borderColor='#dc3545'">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="/cart/add-from-form" style="margin: 0;">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                                <input type="hidden" name="quantite" value="1">
                                <button type="submit" 
                                        style="width: 100%; padding: 12px 20px; background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); color: white; border: 2px solid #dc3545; border-radius: 12px; cursor: pointer; font-size: 14px; font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease;"
                                        <?= $product['stock'] <= 0 ? 'disabled style="width: 100%; padding: 12px 20px; background: #cbd5e0; color: #a0aec0; border: 2px solid #a0aec0; border-radius: 12px; cursor: not-allowed; font-size: 14px; font-weight: 600;" title="Stock épuisé"' : '' ?>
                                        onmouseover="<?= $product['stock'] > 0 ? "this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220, 53, 69, 0.4)'; this.style.borderColor='#dc3545'" : '' ?>"
                                        onmouseout="<?= $product['stock'] > 0 ? "this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)'; this.style.borderColor='#dc3545'" : '' ?>">
                                    Ajouter au panier
                                </button>
                            </form>
                        </div>
                        
                        <?php if ($isAdmin): ?>
                            <div style="margin-top: 15px; font-size: 12px; color: #cbd5e0; flex-shrink: 0; text-align: center; font-weight: 500;">
                                ID: <?= htmlspecialchars($product['id']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div style="margin-top: 40px; display: flex; justify-content: space-between; align-items: center;">
        <a href="/" style="color: #dc3545; text-decoration: none; font-weight: 500; transition: all 0.3s ease;"
           onmouseover="this.style.color='#c53030'; this.style.textDecoration='underline'"
           onmouseout="this.style.color='#dc3545'; this.style.textDecoration='none'">
            ← Retour à l'accueil
        </a>
        <a href="/cart" 
           style="padding: 12px 24px; background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease; border: 2px solid #dc3545;"
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220, 53, 69, 0.4)'; this.style.borderColor='#dc3545'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)'; this.style.borderColor='#dc3545'">
            Voir mon panier
        </a>
    </div>
</div>

