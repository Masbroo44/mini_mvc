<?php

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

class User
{
    private $id;
    private $nom;
    private $email;
    private $password;
    private $adresse;
    private $role; // <--- AJOUTÉ : Propriété manquante

    // =====================
    // Getters / Setters
    // =====================

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNom() { return $this->nom; }
    public function setNom($nom) { $this->nom = $nom; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }

    public function getAdresse() { return $this->adresse; }
    public function setAdresse($adresse) { $this->adresse = $adresse; }

    public function getRole() { return $this->role; }
    public function setRole($role) { $this->role = $role; }

    // =====================
    // Méthodes CRUD
    // =====================

    public static function getAll()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("SELECT * FROM `user` ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM `user` WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByEmail($email)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM `user` WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function save()
    {
        $pdo = Database::getPDO();
        
        // SÉCURITÉ : Si le rôle n'est pas défini, on met "client" par défaut
        if (empty($this->role)) {
            $this->role = 'client';
        }

        // Note : J'ai utilisé `mot_de_passe` car c'est le nom dans votre SQL précédent
        // Si votre colonne s'appelle vraiment `password`, gardez password.
        $stmt = $pdo->prepare("INSERT INTO `user` (nom, email, password, adresse, role) VALUES (?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $this->nom, 
            $this->email, 
            $this->password, 
            $this->adresse, 
            $this->role
        ]);
    }

    public function update()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("UPDATE `user` SET nom = ?, email = ?, adresse = ?, role = ? WHERE id = ?");
        return $stmt->execute([$this->nom, $this->email, $this->adresse, $this->role, $this->id]);
    }

    public function delete()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM `user` WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}