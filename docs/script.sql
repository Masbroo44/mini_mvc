CREATE TABLE categorie (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image_url TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE INDEX idx_categorie_nom ON categorie (nom);

CREATE TABLE administrateur (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    mdp_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'super_admin') NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE client (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    mdp_hash VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    adresse VARCHAR(255),
    ville VARCHAR(100),
    code_postal VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE produit (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    categorie_id INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    description_detaillee TEXT NOT NULL,
    prix_unitaire_courant DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    est_actif BOOLEAN NOT NULL DEFAULT TRUE,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_produit_categorie FOREIGN KEY (categorie_id) REFERENCES categorie(id_categorie)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT chk_prix_produit CHECK (prix_unitaire_courant >= 0),
    CONSTRAINT chk_stock_produit CHECK (stock >= 0)
);

CREATE INDEX idx_produit_categorie ON produit (categorie_id);
CREATE INDEX idx_produit_nom ON produit (nom);

CREATE TABLE commande (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    numero_unique VARCHAR(50) NOT NULL UNIQUE,
    date_commande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'payée', 'expédiée', 'livrée', 'annulée') NOT NULL DEFAULT 'en_attente',
    montant_total DECIMAL(10, 2) NOT NULL,
    adresse_livraison VARCHAR(255) NOT NULL,
    ville_livraison VARCHAR(100) NOT NULL,
    code_postal_livraison VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_commande_client FOREIGN KEY (client_id) REFERENCES client(id_client)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT chk_montant_total CHECK (montant_total >= 0)
);

CREATE INDEX idx_commande_client ON commande (client_id);
CREATE INDEX idx_commande_statut ON commande (statut);

CREATE TABLE ligne_commande (
    id_ligne INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    produit_id INT NOT NULL,

    quantite INT NOT NULL,
    prix_unitaire_commande DECIMAL(10, 2) NOT NULL,
    sous_total DECIMAL(10, 2) NOT NULL,
    
    UNIQUE KEY uk_ligne_commande (commande_id, produit_id), 

    CONSTRAINT fk_ligne_commande_commande FOREIGN KEY (commande_id) REFERENCES commande(id_commande)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ligne_commande_produit FOREIGN KEY (produit_id) REFERENCES produit(id_produit)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT chk_quantite_ligne CHECK (quantite > 0),
    CONSTRAINT chk_prix_ligne CHECK (prix_unitaire_commande >= 0)
);