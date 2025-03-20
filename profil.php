<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Veuillez vous connecter pour accéder à votre profil.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Récup les info du user
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if ($email) {
        // Change les info dans la base de donnée
        $updateStmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $updateStmt->execute([
            'name' => $name,
            'email' => $email,
            'id' => $user_id
        ]);

        echo "Profil mis à jour avec succès.";
        header("Refresh: 2; url=profil.php"); // refresh la page apres 2 secondes
    } else {
        echo "Email invalide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="profile-container">
    <h2>👤 Mon Profil</h2>

    <div class="profile-info">
        <p><strong>Nom :</strong> <?= htmlspecialchars($user['name']); ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']); ?></p>
    </div>

    <h3>Modifier mes informations</h3>
    <form action="profil.php" method="POST">
        <label>Nom :</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required><br>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required><br>

        <button type="submit">Mettre à jour</button>
    </form>

    <a href="search.php" class="back-btn">🏠 Retour</a>
</div>

<style>

.profile-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.profile-info p {
    font-size: 18px;
    color: #333;
}

input, button {
    display: block;
    width: 90%;
    padding: 10px;
    margin: 10px auto;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background: #28a745;
    color: white;
    cursor: pointer;
}

button:hover {
    background: #218838;
}

.back-btn {
    display: inline-block;
    padding: 10px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 10px;
}

.back-btn:hover {
    background: #0056b3;
}

</style>

</body>
</html>
