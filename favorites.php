<?php
session_start();
require_once 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirection vers la page de connexion
    exit;
}

$userId = $_SESSION['user_id']; // Récupération de l'ID de l'utilisateur connecté

// Récupérer les livres favoris de utilisateur
$stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ?");
$stmt->execute([$userId]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récup les favoris avec tableau associatif
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Favoris</title>
</head>
<body>

<div class="container">
    <h2>📚 Mes Livres Favoris</h2>
    <ul>
        <?php foreach ($favorites as $fav): ?>
            <li>
                <img src="<?= htmlspecialchars($fav['thumbnail']) ?>" style="width:100px;"><br>
                <strong><?= htmlspecialchars($fav['title']) ?></strong> - <?= htmlspecialchars($fav['authors']) ?><br>

                <!-- Formulaire pour supprimer un favori -->
                <form class="delete-btn" action="remove_favorite.php" method="POST">
                    <input type="hidden" name="fav_id" value="<?= $fav['id'] ?>">
                    <button type="submit">❌ Supprimer</button> 
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="search.php" class="back-btn">🔍 Retour à la recherche</a>

</div>
    
</body>

<style> 

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    text-align: center;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
}

ul {
    list-style: none;
    padding: 0;
}

li {
    background: #fff;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

button, .delete-btn {
    background-color: #ff4d4d;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
    text-decoration: none;
}

button:hover, .delete-btn:hover {
    background-color: #cc0000;
}


.back-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 15px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.back-btn:hover {
    background-color: #0056b3;
}

</style>

</html>
