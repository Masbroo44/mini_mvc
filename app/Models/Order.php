<?php

namespace Mini\Models;

use Mini\Core\Database;
use Mini\Models\Cart;
use Mini\Models\Product;
use PDO;

class Order
{
    private $id;
    private $user_id;
    private $statut;
    private $total;
    private $date;

    // =====================
    // Getters / Setters
    // =====================

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }

    public function getStatut() { return $this->statut; }
    public function setStatut($statut) { $this->statut = $statut; }

    public function getTotal() { return $this->total; }
    public function setTotal($total) { $this->total = $total; }

    public function getDate() { return $this->date; }
    public function setDate($date) { $this->date = $date; }

    // =====================
    // Méthodes CRUD
    // =====================

    /**
     * Récupère toutes les commandes d'un utilisateur
     */
    public static function getByUserId($user_id)
    {
        $pdo = Database::getPDO();
        // Alias SQL : date AS created_at pour satisfaire la vue
        $stmt = $pdo->prepare("
            SELECT id, user_id, statut, total, date AS created_at 
            FROM commande 
            WHERE user_id = ? 
            ORDER BY date DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les commandes validées (Admin)
     */
    public static function getValidatedOrders()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("
            SELECT c.id, c.user_id, c.statut, c.total, c.date AS created_at, 
                   u.nom as user_nom, u.email as user_email
            FROM commande c
            INNER JOIN `user` u ON c.user_id = u.id
            WHERE c.statut = 'validee'
            ORDER BY c.date DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une commande par son ID avec ses produits
     */
    public static function findByIdWithProducts($id)
    {
        $pdo = Database::getPDO();
        
        $stmt = $pdo->prepare("
            SELECT c.id, c.user_id, c.statut, c.total, c.date AS created_at, 
                   u.nom as user_nom, u.email as user_email
            FROM commande c
            INNER JOIN `user` u ON c.user_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) return null;
        
        // Récupération des articles via la table order_item
        $stmt = $pdo->prepare("
            SELECT oi.*, p.nom as product_nom, p.image_url, cat.nom as categorie_nom
            FROM order_item oi
            INNER JOIN produit p ON oi.produit_id = p.id
            LEFT JOIN categorie cat ON p.categorie_id = cat.id
            WHERE oi.commande_id = ?
        ");
        $stmt->execute([$id]);
        $order['products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $order;
    }

    /**
     * Crée une commande à partir du panier
     */
    public static function createFromCart($user_id)
    {
        $pdo = Database::getPDO();
        $cartItems = Cart::getByUserId($user_id);
        
        if (empty($cartItems)) return false;
        
        $total = Cart::getTotalByUserId($user_id);
        
        try {
            $pdo->beginTransaction();
            
            // Insertion dans 'commande' avec la date actuelle
            $stmt = $pdo->prepare("INSERT INTO commande (user_id, statut, total, date) VALUES (?, 'validee', ?, NOW())");
            $stmt->execute([$user_id, $total]);
            
            $orderId = $pdo->lastInsertId();
            
            // Insertion des détails dans 'order_item'
            $stmt = $pdo->prepare("INSERT INTO order_item (commande_id, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
            
            foreach ($cartItems as $item) {
                $product = Product::findById($item['id']);
                if ($product) {
                    $stmt->execute([
                        $orderId,
                        $item['id'],
                        $item['quantite'],
                        $product['prix']
                    ]);
                    
                    // Mise à jour du stock
                    $newStock = $product['stock'] - $item['quantite'];
                    $updateStmt = $pdo->prepare("UPDATE produit SET stock = ? WHERE id = ?");
                    $updateStmt->execute([$newStock, $item['id']]);
                }
            }
            
            Cart::clearByUserId($user_id);
            $pdo->commit();
            return $orderId;
            
        } catch (\Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public function update()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("UPDATE commande SET statut = ?, total = ? WHERE id = ?");
        return $stmt->execute([$this->statut, $this->total, $this->id]);
    }

    public function delete()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM commande WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}