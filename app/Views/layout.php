<!doctype html>
<!-- Définit la langue du document -->
<html lang="fr">
<!-- En-tête du document HTML -->
<head>
    <!-- Déclare l'encodage des caractères -->
    <meta charset="utf-8">
    <!-- Configure le viewport pour les appareils mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Définit le titre de la page avec échappement -->
    <title><?= isset($title) ? htmlspecialchars($title) . ' - HolyGoods' : 'HolyGoods - Votre boutique en ligne' ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            min-height: 100vh;
            color: #2d3748;
            line-height: 1.6;
        }
        a {
            text-decoration: none;
            transition: all 0.3s ease;
        }
        button {
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }
        input, textarea, select {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<!-- Corps du document -->
<body>
<?php
// Détermine la page active pour la navigation
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$isHome = ($currentPath === '/');
$isProducts = ($currentPath === '/products' || strpos($currentPath, '/products/show') === 0);
$isProductsCreate = ($currentPath === '/products/create');
$isUsersCreate = ($currentPath === '/users/create');
$isCart = ($currentPath === '/cart');
$isOrders = (strpos($currentPath, '/orders') === 0);
$isLogin = ($currentPath === '/login');
$isRegister = ($currentPath === '/register');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$loggedUserId = $_SESSION['user_id'] ?? null;
$loggedUserName = $_SESSION['user_nom'] ?? null;
?>
<!-- En-tête de la page -->
<header style="background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%); color: white; padding: 20px 0; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); position: sticky; top: 0; z-index: 1000; backdrop-filter: blur(10px); border-bottom: 3px solid #dc3545;">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <!-- Logo/Titre -->
        <h1 style="margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;">
            <a href="/" style="color: white; text-decoration: none; display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 32px; color: #dc3545;">⚡</span>
                <span>Holy<span style="color: #dc3545;">Goods</span></span>
            </a>
        </h1>
        
        <!-- Navigation -->
        <nav>
            <ul style="list-style: none; margin: 0; padding: 0; display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <li>
                    <a href="/" 
                       style="color: <?= $isHome ? '#dc3545' : 'white' ?>; 
                              text-decoration: none; 
                              padding: 10px 18px; 
                              border-radius: 8px;
                              display: inline-block;
                              font-weight: <?= $isHome ? '600' : '400' ?>;
                              background: <?= $isHome ? 'rgba(220, 53, 69, 0.2)' : 'transparent' ?>;
                              transition: all 0.3s ease;"
                       onmouseover="this.style.backgroundColor='rgba(220, 53, 69, 0.2)'; this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.backgroundColor='<?= $isHome ? 'rgba(220, 53, 69, 0.2)' : 'transparent' ?>'; this.style.transform='translateY(0)'">
                        🏠 Accueil
                    </a>
                </li>
                <li>
                    <a href="/products" 
                       style="color: <?= $isProducts ? '#dc3545' : 'white' ?>; 
                              text-decoration: none; 
                              padding: 10px 18px; 
                              border-radius: 8px;
                              display: inline-block;
                              font-weight: <?= $isProducts ? '600' : '400' ?>;
                              background: <?= $isProducts ? 'rgba(220, 53, 69, 0.2)' : 'transparent' ?>;
                              transition: all 0.3s ease;"
                       onmouseover="this.style.backgroundColor='rgba(220, 53, 69, 0.2)'; this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.backgroundColor='<?= $isProducts ? 'rgba(220, 53, 69, 0.2)' : 'transparent' ?>'; this.style.transform='translateY(0)'">
                        📦 Produits
                    </a>
                </li>
                <?php if ($loggedUserId && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <li>
                        <a href="/products/create" 
                           style="color: <?= $isProductsCreate ? '#ffd700' : 'white' ?>; 
                                  text-decoration: none; 
                                  padding: 10px 18px; 
                                  border-radius: 8px;
                                  display: inline-block;
                                  font-weight: <?= $isProductsCreate ? '600' : '400' ?>;
                                  background: <?= $isProductsCreate ? 'rgba(255,255,255,0.2)' : 'transparent' ?>;
                                  transition: all 0.3s ease;"
                           onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'; this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.backgroundColor='<?= $isProductsCreate ? 'rgba(255,255,255,0.2)' : 'transparent' ?>'; this.style.transform='translateY(0)'">
                            ➕ Ajouter un produit
                        </a>
                    </li>
                <?php endif; ?>
                <!-- <li>
                    <a href="/users/create" 
                       style="color: <?= $isUsersCreate ? '#ffc107' : 'white' ?>; 
                              text-decoration: none; 
                              padding: 8px 15px; 
                              border-radius: 4px;
                              display: inline-block;
                              transition: background-color 0.3s;"
                       onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'"
                       onmouseout="this.style.backgroundColor='transparent'">
                        👤 Ajouter un utilisateur
                    </a>
                </li> -->
                <li>
                    <a href="/cart" 
                       style="color: <?= $isCart ? '#ffd700' : 'white' ?>; 
                              text-decoration: none; 
                              padding: 10px 18px; 
                              border-radius: 8px;
                              display: inline-block;
                              font-weight: <?= $isCart ? '600' : '400' ?>;
                              background: <?= $isCart ? 'rgba(255,255,255,0.2)' : 'transparent' ?>;
                              transition: all 0.3s ease;"
                       onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'; this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.backgroundColor='<?= $isCart ? 'rgba(255,255,255,0.2)' : 'transparent' ?>'; this.style.transform='translateY(0)'">
                        🛒 Panier
                    </a>
                </li>
                <li>
                    <a href="/orders" 
                       style="color: <?= $isOrders ? '#ffd700' : 'white' ?>; 
                              text-decoration: none; 
                              padding: 10px 18px; 
                              border-radius: 8px;
                              display: inline-block;
                              font-weight: <?= $isOrders ? '600' : '400' ?>;
                              background: <?= $isOrders ? 'rgba(255,255,255,0.2)' : 'transparent' ?>;
                              transition: all 0.3s ease;"
                       onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'; this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.backgroundColor='<?= $isOrders ? 'rgba(255,255,255,0.2)' : 'transparent' ?>'; this.style.transform='translateY(0)'">
                        📋 Mes commandes
                    </a>
                </li>
                <?php if ($loggedUserId): ?>
                    <li style="margin-left: 15px; padding: 8px 15px; background: rgba(220, 53, 69, 0.2); border-radius: 8px; font-weight: 500;">
                        👤 <?= htmlspecialchars($loggedUserName ?? 'Utilisateur') ?>
                    </li>
                    <li>
                        <a href="/logout"
                           style="color: white;
                                  text-decoration: none;
                                  padding: 10px 18px;
                                  border-radius: 8px;
                                  display: inline-block;
                                  background: rgba(220, 53, 69, 0.2);
                                  transition: all 0.3s ease;"
                           onmouseover="this.style.backgroundColor='rgba(220, 53, 69, 0.3)'; this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.backgroundColor='rgba(220, 53, 69, 0.2)'; this.style.transform='translateY(0)'">
                            🚪 Déconnexion
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="/login"
                           style="color: <?= $isLogin ? '#dc3545' : 'white' ?>;
                                  text-decoration: none;
                                  padding: 10px 18px;
                                  border-radius: 8px;
                                  display: inline-block;
                                  font-weight: <?= $isLogin ? '600' : '400' ?>;
                                  background: <?= $isLogin ? 'rgba(220, 53, 69, 0.2)' : 'transparent' ?>;
                                  transition: all 0.3s ease;"
                           onmouseover="this.style.backgroundColor='rgba(220, 53, 69, 0.2)'; this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.backgroundColor='<?= $isLogin ? 'rgba(220, 53, 69, 0.2)' : 'transparent' ?>'; this.style.transform='translateY(0)'">
                            🔐 Connexion
                        </a>
                    </li>
                    <li>
                        <a href="/register"
                           style="color: white;
                                  text-decoration: none;
                                  padding: 10px 20px;
                                  border-radius: 8px;
                                  display: inline-block;
                                  background: rgba(220, 53, 69, 0.3);
                                  font-weight: 500;
                                  transition: all 0.3s ease;"
                           onmouseover="this.style.backgroundColor='rgba(220, 53, 69, 0.4)'; this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.backgroundColor='rgba(220, 53, 69, 0.3)'; this.style.transform='translateY(0)'">
                            ✍️ Inscription
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<!-- Zone de contenu principal -->
<main style="min-height: calc(100vh - 200px); padding-bottom: 40px;">
    <!-- Insère le contenu rendu de la vue -->
    <?= $content ?>
</main>
<!-- Fin du corps de la page -->
</body>
<!-- Fin du document HTML -->
</html>

