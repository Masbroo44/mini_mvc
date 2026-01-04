CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    adresse VARCHAR(255),
    role VARCHAR(50) NOT NULL
);

CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT
);

CREATE TABLE produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    categorie_id INT NOT NULL,

    CONSTRAINT fk_produit_categorie
        FOREIGN KEY (categorie_id)
        REFERENCES categorie(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_produit_categorie ON produit(categorie_id);

CREATE TABLE commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME NOT NULL,
    statut VARCHAR(50) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    
    user_id INT NOT NULL,

    
    CONSTRAINT fk_commande_user
        FOREIGN KEY (user_id)
        REFERENCES `user`(id) 
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


CREATE INDEX idx_commande_user ON commande(user_id);

CREATE TABLE order_item (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    commande_id INT NOT NULL,
    produit_id INT NOT NULL,

    CONSTRAINT fk_orderitem_commande
        FOREIGN KEY (commande_id)
        REFERENCES commande(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_orderitem_produit
        FOREIGN KEY (produit_id)
        REFERENCES produit(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_orderitem_commande ON order_item(commande_id);
CREATE INDEX idx_orderitem_produit ON order_item(produit_id);