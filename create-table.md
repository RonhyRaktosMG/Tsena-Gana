# 📦 TsenaGana – Base de données (version propre et finale)

---

# 🌍 region

```sql
CREATE TABLE region (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);
```

---

# 👤 utilisateur

```sql
CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    adresse VARCHAR(255),
    role ENUM('client','vendeur','eleveur','livreur') NOT NULL,
    region_id INT,

    FOREIGN KEY (region_id) REFERENCES region(id)
);
```

---

# 💰 portefeuille

```sql
CREATE TABLE portefeuille (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT UNIQUE,
    solde DECIMAL(12,2) DEFAULT 0,

    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id)
);
```

---

# 💸 transaction

```sql
CREATE TABLE transaction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    portefeuille_id INT,
    type ENUM('depot','retrait','paiement') NOT NULL,
    montant DECIMAL(12,2) NOT NULL,
    status ENUM('en_attente','valide','refuse') DEFAULT 'en_attente',
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (portefeuille_id) REFERENCES portefeuille(id)
);
```

---

# 🦆 lot\_canard

```sql
CREATE TABLE lot_canard (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendeur_id INT,

    race VARCHAR(100),
    date_naissance DATE,
    poids_moyen DECIMAL(6,2),

    prix_unitaire DECIMAL(10,2),
    quantite_stock INT DEFAULT 0,
    quantite_reserve INT DEFAULT 0,
    disponible BOOLEAN DEFAULT TRUE,

    image VARCHAR(255),

    FOREIGN KEY (vendeur_id) REFERENCES utilisateur(id)
);
```

---

# 🛒 produit

```sql
CREATE TABLE produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendeur_id INT,

    nom VARCHAR(100),
    type VARCHAR(100),
    prix_unitaire DECIMAL(10,2),
    stock INT DEFAULT 0,
    unite ENUM('kg','piece','litre'),
    image VARCHAR(255),

    FOREIGN KEY (vendeur_id) REFERENCES utilisateur(id)
);
```

---

# 🧱 commande (globale)

```sql
CREATE TABLE commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente','en_cours','livree','annulee') DEFAULT 'en_attente',

    FOREIGN KEY (client_id) REFERENCES utilisateur(id)
);
```

---

# 📦 ligne\_commande (panier client)

```sql
CREATE TABLE ligne_commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,

    type_article ENUM('canard','produit') NOT NULL,
    article_id INT NOT NULL,

    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (commande_id) REFERENCES commande(id)
);
```

---

# 🚚 distribution\_commande (par vendeur)

```sql
CREATE TABLE distribution_commande (
    id INT AUTO_INCREMENT PRIMARY KEY,

    ligne_commande_id INT NOT NULL,
    vendeur_id INT NOT NULL,

    quantite_a_livrer INT NOT NULL,

    statut ENUM('en_attente','prepare','livre') DEFAULT 'en_attente',

    FOREIGN KEY (ligne_commande_id) REFERENCES ligne_commande(id),
    FOREIGN KEY (vendeur_id) REFERENCES utilisateur(id)
);
```

---

# 🚛 livraison (par distribution)

👉 IMPORTANT : une commande peut avoir plusieurs livraisons

```sql
CREATE TABLE livraison (
    id INT AUTO_INCREMENT PRIMARY KEY,
    distribution_id INT NOT NULL,

    adresse_depart VARCHAR(255),
    adresse_arrivee VARCHAR(255),

    date_depart DATETIME,
    date_prevue DATETIME,
    date_reelle DATETIME,

    statut ENUM('en_preparation','en_cours','livree') DEFAULT 'en_preparation',

    FOREIGN KEY (distribution_id) REFERENCES distribution_commande(id)
);
```

---

# ⭐ evaluation

```sql
CREATE TABLE evaluation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    auteur_id INT,
    cible_id INT,

    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire VARCHAR(255),
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (auteur_id) REFERENCES utilisateur(id),
    FOREIGN KEY (cible_id) REFERENCES utilisateur(id)
);
```

---

# 💳 paiement

```sql
CREATE TABLE paiement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT,
    utilisateur_id INT,

    montant DECIMAL(12,2),
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (commande_id) REFERENCES commande(id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id)
);
```

---

# 🧠 Architecture finale (très important)

```text
COMMANDE (client)
   ↓
LIGNE_COMMANDE (produits demandés)
   ↓
DISTRIBUTION_COMMANDE (répartition par vendeur)
   ↓
LIVRAISON (chaque livraison réelle)
```
