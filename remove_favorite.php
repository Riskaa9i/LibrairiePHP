<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Vérifie si le favoris a été selectionner pour le supprimer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fav_id'])) {
    $favId = $_POST['fav_id']; // recup l'id 

    // Requete pour supprimer le favori
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE id = ?");
    $stmt->execute([$favId]);

    header("Location: favorites.php");
    exit;
}
?>
