Bonne idée 👍 — séparer les requêtes par type de vendeur va te simplifier la logique et éviter les `CASE` / `COALESCE` inutiles.

👉 Tu fais donc **2 espaces** :

* 🦆 éleveur (lot\_canard)
* 🛒 vendeur produit (produit)

Et chacun a **ses propres requêtes**.

---

# 🦆 1. ESPACE ÉLEVEUR (vendeur de canard)

## 📥 Commandes reçues (canards uniquement)

```php
<?php
$vendeur_id = 2;

$sql = "
SELECT 
    c.id AS numero_commande,
    c.date_commande,
    u.nom AS nom_acheteur,

    MIN(dc.statut) AS statut,

    SUM(dc.quantite_a_livrer * lc2.prix_unitaire) AS total

FROM distribution_commande dc

JOIN ligne_commande lc ON lc.id = dc.ligne_commande_id
JOIN lot_canard lc2 ON lc.article_id = lc2.id
JOIN commande c ON c.id = lc.commande_id
JOIN utilisateur u ON u.id = c.client_id

WHERE 
    dc.vendeur_id = :vendeur_id
    AND lc.type_article = 'canard'

GROUP BY c.id
ORDER BY c.date_commande DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['vendeur_id' => $vendeur_id]);

$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
```

---

## 📦 Articles à livrer (canards)

```php
<?php
function getCanards($pdo, $commande_id, $vendeur_id) {

    $sql = "
    SELECT 
        lc2.race,
        lc2.prix_unitaire,
        dc.quantite_a_livrer

    FROM distribution_commande dc

    JOIN ligne_commande lc ON lc.id = dc.ligne_commande_id
    JOIN lot_canard lc2 ON lc.article_id = lc2.id

    WHERE 
        dc.vendeur_id = :vendeur_id
        AND lc.type_article = 'canard'
        AND lc.commande_id = :commande_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'vendeur_id' => $vendeur_id,
        'commande_id' => $commande_id
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
```

---

# 🛒 2. ESPACE VENDEUR PRODUIT

## 📥 Commandes reçues (produits uniquement)

```php
<?php
$vendeur_id = 2;

$sql = "
SELECT 
    c.id AS numero_commande,
    c.date_commande,
    u.nom AS nom_acheteur,

    MIN(dc.statut) AS statut,

    SUM(dc.quantite_a_livrer * p.prix_unitaire) AS total

FROM distribution_commande dc

JOIN ligne_commande lc ON lc.id = dc.ligne_commande_id
JOIN produit p ON lc.article_id = p.id
JOIN commande c ON c.id = lc.commande_id
JOIN utilisateur u ON u.id = c.client_id

WHERE 
    dc.vendeur_id = :vendeur_id
    AND lc.type_article = 'produit'

GROUP BY c.id
ORDER BY c.date_commande DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['vendeur_id' => $vendeur_id]);

$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
```

---

## 📦 Articles à livrer (produits)

```php
<?php
function getProduits($pdo, $commande_id, $vendeur_id) {

    $sql = "
    SELECT 
        p.nom,
        p.prix_unitaire,
        dc.quantite_a_livrer

    FROM distribution_commande dc

    JOIN ligne_commande lc ON lc.id = dc.ligne_commande_id
    JOIN produit p ON lc.article_id = p.id

    WHERE 
        dc.vendeur_id = :vendeur_id
        AND lc.type_article = 'produit'
        AND lc.commande_id = :commande_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'vendeur_id' => $vendeur_id,
        'commande_id' => $commande_id
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
```

---

# ✅ Avantages de cette approche

✔ Requêtes **plus simples et lisibles**
✔ Pas de `CASE` compliqué
✔ Meilleure performance (index mieux utilisés)
✔ Code plus clair côté front
✔ Chaque espace = logique métier propre

---

# 🔥 Conseil pro (très important)

Même si tu sépares les espaces :

👉 **garde `distribution_commande` comme source unique**

C’est elle qui garantit :

* qui livre quoi
* combien
* statut par vendeur

---

# 💡 Bonus (si tu veux aller plus loin)

Tu peux factoriser avec :

* une classe `CommandeVendeur`
* ou une API unique avec paramètre `type=canard|produit`

---

Si tu veux, prochaine étape je peux te faire :

* ✅ dashboard vendeur (revenus, graphiques)
* ✅ bouton “préparer / livrer”
* ✅ séparation automatique des commandes multi-vendeurs (logique back)

Dis-moi 👍
