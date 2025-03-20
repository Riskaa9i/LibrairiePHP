<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Vérifie si les données du livre sont envoyées et les récupère
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['book_id'])) {
    $userId = $_SESSION['user_id'];
    $bookId = $_POST['book_id'];
    $title = $_POST['title'];
    $authors = $_POST['authors'];
    $thumbnail = $_POST['thumbnail'];

try {
    // Vérifie si le livre est déjà en favoris
    $checkStmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND book_id = ?");
    $checkStmt->execute([$userId, $bookId]);

    if ($checkStmt->rowCount() == 0) { // Vérifie si aucun résultat n'est trouvé
            // Mettre dans la base de données
        $stmt = $pdo->prepare("INSERT INTO favorites (user_id, book_id, title, authors, thumbnail) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $bookId, $title, $authors, $thumbnail]);
        echo " 📚 Livre ajouté aux favoris !";
    echo '<script>
        setTimeout(function() {
            window.location.href = "search.php";
            }, 3000);
        </script>';
    } else {
        echo "⚠️ Ce livre est déjà dans vos favoris.";
        }
    echo '<script>
        setTimeout(function() {
            window.location.href = "search.php";
            }, 3000);
        </script>';
} catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
}

}
?>
