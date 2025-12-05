**Pourquoi stocker le prix unitaire dans la table des lignes de commande plutôt que d'utiliser directement le prix du produit ?**

Stocker le prix unitaire dans la ligne de commande est essentiel. Une commande est un document légal et comptable. Le prix doit être celui de l'achat. Le prix du produit peut changer plus tard. L'enregistrement garantit l'historique précis.

**Quelle stratégie avez-vous choisie pour gérer les suppressions ? Justifiez vos choix pour chaque relation.**

La stratégie de suppression est mixte. J'utilise **`ON DELETE RESTRICT`** pour les données critiques. La suppression de clients ou de produits dans une commande est bloquée. Ceci préserve l'historique transactionnel. Je privilégie une suppression logique. J'utilise **`ON DELETE CASCADE`** pour les dépendances internes. Supprimer une commande supprime automatiquement ses lignes de détail.

**Comment gérez-vous les stocks ? Que se passe-t-il si un client commande un produit en rupture de stock ? Quand le stock est-il décrémenté (panier, validation, paiement) ?**

La gestion des stocks est faite par l'application. Si le stock est insuffisant, la commande est bloquée. Le client reçoit un message d'erreur. Le stock est décrémenté au moment du paiement validé. Ceci se fait dans une transaction SQL atomique. Cela évite le blocage inutile du stock dans le panier.

**Avez-vous prévu des index ? Lesquels et pourquoi ?**

Oui, des index sont prévus. Tous les champs de clé étrangère sont indexés. Cela accélère les jointures. Les colonnes de recherche et de filtrage sont aussi indexées. J'ai indexé `produit.nom` pour la recherche. J'ai indexé `commande.statut` pour le back-office.

**Comment assurez-vous l'unicité du numéro de commande ?**

L'unicité du numéro de commande est assurée. J'utilise la contrainte **`UNIQUE`** sur la colonne `numero_unique`. Cela bloque l'insertion de doublons dans la base de données.

**Quelles sont les extensions possibles de votre modèle ?**

Le modèle peut être facilement étendu. On pourrait ajouter une table pour gérer plusieurs adresses par client. L'historique des prix nécessiterait une nouvelle table. On peut créer une table pour les avis clients. Une table distincte permettrait de gérer les images multiples par produit.