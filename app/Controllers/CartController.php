<?php

declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Cart;
use Mini\Models\Product;

final class CartController extends Controller
{
    /**
     * Affiche le panier d'un utilisateur
     */
    public function show(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Utilise uniquement le user_id de la session (utilisateur connecté)
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }
        
        $user_id = (int) $_SESSION['user_id'];
        
        // Ici je récupère les produits du panier de l'user authentifié
        $cartItems = Cart::getByUserId($user_id);
        // Ici on récupère le prix total du panier
        $total = Cart::getTotalByUserId($user_id);
        
        $message = null;
        $messageType = null;
        
        if (isset($_GET['success'])) {
            if ($_GET['success'] === 'added') {
                $message = 'Produit ajouté au panier avec succès !';
                $messageType = 'success';
            } elseif ($_GET['success'] === 'updated') {
                $message = 'Quantité mise à jour avec succès !';
                $messageType = 'success';
            } elseif ($_GET['success'] === 'removed') {
                $message = 'Article supprimé du panier avec succès !';
                $messageType = 'success';
            } elseif ($_GET['success'] === 'cleared') {
                $message = 'Panier vidé avec succès !';
                $messageType = 'success';
            }
        }
        
        if (isset($_GET['error'])) {
            if ($_GET['error'] === 'stock_insuffisant') {
                $message = 'Stock insuffisant pour cette quantité.';
                $messageType = 'error';
            } elseif ($_GET['error'] === 'update_failed') {
                $message = 'Erreur lors de la mise à jour.';
                $messageType = 'error';
            } elseif ($_GET['error'] === 'delete_failed') {
                $message = 'Erreur lors de la suppression.';
                $messageType = 'error';
            } elseif ($_GET['error'] === 'clear_failed') {
                $message = 'Erreur lors du vidage du panier.';
                $messageType = 'error';
            }
        }
        
        $this->render('cart/index', params: [
            'title' => 'Mon panier',
            'cartItems' => $cartItems,
            'total' => $total,
            'user_id' => $user_id,
            'message' => $message,
            'messageType' => $messageType
        ]);
    }

    /**
     * Ajoute un produit au panier (API JSON)
     */
    public function add(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée. Utilisez POST.'], JSON_PRETTY_PRINT);
            return;
        }
        
        // Utilise uniquement le user_id de la session (utilisateur connecté)
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non authentifié.'], JSON_PRETTY_PRINT);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input === null) {
            $input = $_POST;
        }
        
        if (empty($input['product_id']) || empty($input['quantite'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Les champs "product_id" et "quantite" sont requis.'], JSON_PRETTY_PRINT);
            return;
        }
        
        $user_id = (int) $_SESSION['user_id'];
        
        // Vérifie que le produit existe
        $product = Product::findById($input['product_id']);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Produit introuvable.'], JSON_PRETTY_PRINT);
            return;
        }
        
        // Vérifie le stock disponible
        if ($product['stock'] < $input['quantite']) {
            http_response_code(400);
            echo json_encode(['error' => 'Stock insuffisant.'], JSON_PRETTY_PRINT);
            return;
        }
        
        $cart = new Cart();
        $cart->setUserId($user_id);
        $cart->setProductId($input['product_id']);
        $cart->setQuantite($input['quantite']);
        
        if ($cart->save()) {
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Produit ajouté au panier avec succès.'
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'ajout au panier.'], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Ajoute un produit au panier depuis un formulaire HTML
     */
    public function addFromForm(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /products');
            return;
        }
        
        // Utilise uniquement le user_id de la session (utilisateur connecté)
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }
        
        $product_id = $_POST['product_id'] ?? null;
        $quantite = intval($_POST['quantite'] ?? 1);
        $user_id = (int) $_SESSION['user_id'];
        
        if (!$product_id) {
            header('Location: /products');
            return;
        }
        
        // Vérifie que le produit existe
        $product = Product::findById($product_id);
        if (!$product) {
            header('Location: /products');
            return;
        }
        
        // Vérifie le stock disponible
        if ($product['stock'] < $quantite) {
            header('Location: /products/show?id=' . $product_id . '&error=stock_insuffisant');
            return;
        }
        
        $cart = new Cart();
        $cart->setUserId($user_id);
        $cart->setProductId($product_id);
        $cart->setQuantite($quantite);
        
        if ($cart->save()) {
            header('Location: /cart?success=added');
        } else {
            header('Location: /products/show?id=' . $product_id . '&error=add_failed');
        }
    }

    /**
     * Met à jour la quantité d'un produit dans le panier (session)
     */
    public function update(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
            header('Location: /cart');
            return;
        }
        
        // Utilise uniquement le user_id de la session (utilisateur connecté)
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input === null) {
            $input = $_POST;
        }
        
        if (empty($input['cart_id']) || empty($input['quantite'])) {
            header('Location: /cart?error=missing_fields');
            return;
        }
        
        $user_id = (int) $_SESSION['user_id'];
        $product_id = (int) $input['cart_id']; // cart_id est en fait le product_id dans notre système de session
        $quantite = (int) $input['quantite'];
        
        // Vérifie le produit et le stock
        $product = Product::findById($product_id);
        if (!$product) {
            header('Location: /cart?error=item_not_found');
            return;
        }
        
        if ($product['stock'] < $quantite) {
            header('Location: /cart?error=stock_insuffisant');
            return;
        }
        
        $cart = new Cart();
        $cart->setUserId($user_id);
        $cart->setProductId($product_id);
        $cart->setQuantite($quantite);
        
        if ($cart->save()) {
            header('Location: /cart?success=updated');
        } else {
            header('Location: /cart?error=update_failed');
        }
    }

    /**
     * Supprime un article du panier (session)
     */
    public function remove(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            return;
        }
        
        // Utilise uniquement le user_id de la session (utilisateur connecté)
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input === null) {
            $input = $_POST;
        }
        
        $cart_id = $input['cart_id'] ?? $_GET['cart_id'] ?? null;
        $user_id = (int) $_SESSION['user_id'];
        
        if (!$cart_id) {
            header('Location: /cart?error=missing_cart_id');
            return;
        }
        
        if (Cart::deleteById($user_id, (int) $cart_id)) {
            header('Location: /cart?success=removed');
        } else {
            header('Location: /cart?error=delete_failed');
        }
    }

    /**
     * Vide le panier d'un utilisateur (session)
     */
    public function clear(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            return;
        }
        
        // Utilise uniquement le user_id de la session (utilisateur connecté)
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }
        
        $user_id = (int) $_SESSION['user_id'];
        
        if (Cart::clearByUserId($user_id)) {
            header('Location: /cart?success=cleared');
        } else {
            header('Location: /cart?error=clear_failed');
        }
    }
}

