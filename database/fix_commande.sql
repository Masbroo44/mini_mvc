-- Script de correction pour la table commande
-- Exécutez ce script dans votre base de données si vous avez l'erreur "Column not found: user_id"

-- Vérifie si la table commande existe, sinon la crée
CREATE TABLE IF NOT EXISTS commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    statut ENUM('en_attente', 'validee', 'annulee') DEFAULT 'en_attente',
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_commande_user 
        FOREIGN KEY (user_id) 
        REFERENCES user(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Si la table existe déjà mais sans la colonne user_id, l'ajoute
-- (Cette commande échouera si la colonne existe déjà, c'est normal)
ALTER TABLE commande 
    ADD COLUMN IF NOT EXISTS user_id INT NOT NULL AFTER id;

-- Si la colonne existe mais sans contrainte de clé étrangère, l'ajoute
-- (Cette commande échouera si la contrainte existe déjà, c'est normal)
ALTER TABLE commande 
    ADD CONSTRAINT fk_commande_user 
        FOREIGN KEY (user_id) 
        REFERENCES user(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE;

-- Vérifie si la table commande_produit existe, sinon la crée
CREATE TABLE IF NOT EXISTS commande_produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    product_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_commande_produit_commande 
        FOREIGN KEY (commande_id) 
        REFERENCES commande(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    CONSTRAINT fk_commande_produit_produit 
        FOREIGN KEY (product_id) 
        REFERENCES produit(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


