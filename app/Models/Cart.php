<?php

namespace Mini\Models;

use Mini\Models\Product;

/**
 * Gestion du panier en session (sans table SQL `panier`)
 */
class Cart
{
    private $user_id;
    private $product_id;
    private $quantite;

    // =====================
    // Getters / Setters
    // =====================

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = (int) $user_id;
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function setProductId($product_id)
    {
        $this->product_id = (int) $product_id;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($quantite)
    {
        $this->quantite = (int) $quantite;
    }

    // =====================
    // Méthodes "session"
    // =====================

    private static function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Récupère tous les articles du panier d'un utilisateur
     * et renvoie une structure compatible avec la vue.
     *
     * @param int $user_id
     * @return array
     */
    public static function getByUserId($user_id): array
    {
        self::ensureSessionStarted();

        $user_id = (int) $user_id;
        $items = $_SESSION['cart'][$user_id] ?? [];

        $result = [];

        foreach ($items as $productId => $data) {
            $product = Product::findById($productId);
            if (!$product) {
                continue;
            }

            $product['quantite'] = (int) $data['quantite'];
            // On utilise l'id produit comme identifiant panier
            $product['panier_id'] = (int) $productId;

            $result[] = $product;
        }

        return $result;
    }

    /**
     * Récupère un article du panier par user_id et product_id
     *
     * @param int $user_id
     * @param int $product_id
     * @return array|null
     */
    public static function findByUserAndProduct($user_id, $product_id): ?array
    {
        self::ensureSessionStarted();

        $user_id = (int) $user_id;
        $product_id = (int) $product_id;

        if (isset($_SESSION['cart'][$user_id][$product_id])) {
            return $_SESSION['cart'][$user_id][$product_id];
        }

        return null;
    }

    /**
     * Calcule le total du panier d'un utilisateur
     *
     * @param int $user_id
     * @return float
     */
    public static function getTotalByUserId($user_id): float
    {
        self::ensureSessionStarted();

        $user_id = (int) $user_id;
        $items = $_SESSION['cart'][$user_id] ?? [];

        $total = 0.0;

        foreach ($items as $productId => $data) {
            $product = Product::findById($productId);
            if (!$product) {
                continue;
            }

            $total += (float) $product['prix'] * (int) $data['quantite'];
        }

        return $total;
    }

    /**
     * Ajoute ou met à jour un produit dans le panier (session)
     *
     * @return bool
     */
    public function save(): bool
    {
        self::ensureSessionStarted();

        if ($this->user_id === null || $this->product_id === null) {
            return false;
        }

        if (!isset($_SESSION['cart'][$this->user_id])) {
            $_SESSION['cart'][$this->user_id] = [];
        }

        $_SESSION['cart'][$this->user_id][$this->product_id] = [
            'product_id' => $this->product_id,
            'quantite'   => $this->quantite,
        ];

        return true;
    }

    /**
     * Supprime un article du panier pour un utilisateur donné.
     * Ici, "panier_id" est équivalent à product_id.
     *
     * @param int $user_id
     * @param int $panier_id
     * @return bool
     */
    public static function deleteById(int $user_id, int $panier_id): bool
    {
        self::ensureSessionStarted();

        if (isset($_SESSION['cart'][$user_id][$panier_id])) {
            unset($_SESSION['cart'][$user_id][$panier_id]);

            if (empty($_SESSION['cart'][$user_id])) {
                unset($_SESSION['cart'][$user_id]);
            }

            return true;
        }

        return false;
    }

    /**
     * Vide le panier d'un utilisateur
     *
     * @param int $user_id
     * @return bool
     */
    public static function clearByUserId($user_id): bool
    {
        self::ensureSessionStarted();

        $user_id = (int) $user_id;

        if (isset($_SESSION['cart'][$user_id])) {
            unset($_SESSION['cart'][$user_id]);
        }

        return true;
    }
}



