<?php 

// 1 - Pouvoir chercher des livres (genre ou nom) via l'API google Books soit avec JS (fetch) soit avec curl
// 2 - UN système de login et signup pour les Users (si pas login, il peut pas chercher)
// 3 - Une fois connecté le user peut ajouter des livres en favoris qui s'enregistrent en BDD. Il doit pouvoir aussi 
// supprimer ceux qu'il veut effacer => Pour tout ce qui est BDD avec PHP on utilise PDO et de requetes préparées
// 4 - Le user a un espace de profil dont il peut modifier les informations (ex : nom, email, avatar)
// 5 - Faire une doc d'utilisation (format word) qui explique comment fonctionne l'app et comment se servir des fonctionnalités
// 6 - Faire du code propre (indentation)

// Notions à utiliser en PHP : 
// - les superglobales ($_POST, $_GET, $_SESSION)
// - PDO pour se connecter à la BDD 
// - Faire des requetes SQL (et préparées si besoin)
// - cURL pour faire des requetes API (ou sinon fetch avec JS)

// Notions en JS :  
// - fetch pour le call API

// En BDD (phpMyAdmin) :
// - Créer les tables nécessaires (au moins User et Livres)

// Autre remarques :

// PDO : Connexion à la BDD => . On vient créer un objet $pdo qu'on réutilise dans nos pages pour faire nos requetes SQL

// Include et require vont vous permettre d'inclure du code modulable dans vos pages 

// pour les requetes API ou fetch en JS ou curl en php

// Pour le signup et le login => hasher les mdp avant la BDD.

// Vous devrez utiliser les sessions => quand un user se connecte vous démarrez une session 
// On enregistre généralement dans la superglobale $_SESSION lles infos du User resupérées de la BDD (et ainsi utiliser les sessions dans vos différentes pages)

// Pkoi pas utiliser les cookies si besoin (cf $_COOKIES et setcookie())

// Il faudra faire attention aux input (attaques XSS, injections SQL) => NTUI : NEVER TRUST USER INPUT

// Vous pouvez également ajouter composer à votre projet et installer des dépendances si besoin 

// Commenter les parties importantes de votre code


session_start(); // Démarre la session pour vérifier si l'utilisateur est connecté

// Vérifier si l'utilisateur est connecté, sinon rediriger vers login.php
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php");
    exit;
}

// Vérifie si une recherche est effectuée
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['query'])) {
    $search = htmlspecialchars($_POST['query']); // Convertit les caractères spéciaux en html pour éviter les failles XSS et récupère le query (recherche du user)
    $apiKey = "AIzaSyD80UvJvxRJoOY9o9LkiIVV48WknJTZzPE";
    $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($search) . "&key=" . $apiKey; // encode la chaine de caractère pour assurer qu'elle soit formter (ex:20%)

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    $response = curl_exec($ch);
    curl_close($ch);

    $books = json_decode($response, true);
}
?>

<!DOCTYPE html>
<html lang="an">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Livres</title>
</head>
<body>

<nav class="navbar">
    <div class="button-container">

        <a href="profil.php" class="profile-btn">👤 Mon Profil</a>
        <a id = "favoris" href="favorites.php"> Mes livres favoris </a>
    <form action="logout.php" method="POST" id="logoutForm">
        <button id = "logout" type="submit">Déconnexion</button>
    </form>
<   </div>
</nav>

<h2>Recherche de Livres</h2>

    <form id = "formulaire" action="search.php" method="POST">
        <input type="text" name="query" placeholder=" 🔍 Nom du livre ou genre..." required>
        <button type="submit">Rechercher</button>
</form>


</body>

<style>

body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    color: #333;
}

    h2, h3 {
    text-align: center;
    color: #333;
    margin-top: 20px;
}

    #formulaire {
    display: flex;
    justify-content: center;
    margin: 20px;
}

    input[type="text"] {
    padding: 10px;
    width: 300px;
    font-size: 16px;
    border: 2px solid #ddd;
    border-radius: 5px;
    outline: none;
}

    input[type="text"]:focus {
            border-color: #66cc66;
}
.button-container {
    display: flex; 
    justify-content: center ;
    gap: 20px;
    margin-top: 20px;
}

    button {
    padding: 10px 15px;
    font-size: 16px;
    margin-left: 10px;
    background-color: #66cc66;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

    button:hover {
    background-color: #5ba85b;
}

    #favoris {
    display: inline-block;
    padding: 10px 15px;
    background:rgb(255, 17, 0);
    color: white;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
    margin-bottom: 15px;
    transition: background 0.3s ease;
    }
    #favoris:hover {
    background:rgb(177, 0, 0);
}

#logout {
    display: inline-block;
    padding: 10px 15px;
    background:rgb(195, 0, 255);
    color: white;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
    margin-bottom: 15px;
    transition: background 0.3s ease;
    }

    #logout:hover {
    background:rgb(168, 0, 173);
    }

.profile-btn  {
    display: inline-block;
    padding: 10px 15px;
    background: #007bff;
    color: white;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
    margin-bottom: 15px;
    transition: background 0.3s ease;
}

.profile-btn:hover {
    background: #0056b3;
}


    ul {
    list-style-type: none;
    padding: 0;
    margin-top: 20px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

    li {
    background-color: white;
    margin: 10px;
    padding: 20px;
    width: 250px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

    li:hover {
    transform: translateY(-10px);
}

    img {
    width: 100px;
    height: 150px;
    object-fit: cover;
    border-radius: 5px;
}

    strong {
    display: block;
    margin-top: 10px;
    font-size: 18px;
    color: #333;
}

    em {
    color: #888;
    font-style: normal;
}

    p {
    font-size: 14px;
    color: #666;
    margin: 10px 0;
}

    .no-results {
    text-align: center;
    color: #888;
    font-size: 18px;
    margin-top: 20px;
}

</style>

    <?php
    // Affichage des résultats si ils existent dans le tableau
    if (!empty($books['items'])) {
        echo "<h3>Résultats :</h3>";
        echo "<ul>";

        foreach ($books['items'] as $book) { // Chaque élement du tableau est extrait sous la variable book 
            $title = $book['volumeInfo']['title'] ?? "Titre inconnu";
            $authors = isset($book['volumeInfo']['authors']) ? implode(", ", $book['volumeInfo']['authors']) : "Auteur inconnu"; // Si auteur n'existe pas , authors devient : Auteur inconnu
            $description = $book['volumeInfo']['description'] ?? "Pas de description";  
            $thumbnail = $book['volumeInfo']['imageLinks']['thumbnail'] ?? "";
            $publishedDate = $book['volumeInfo']['publishedDate'] ?? "Date de publication inconnue";
            $bookId = $book['id'];
            

            echo "<li>";
            if ($thumbnail) {
                echo "<img src='$thumbnail' alt='Couverture du livre' style='width:100px;'><br>";
            }
            echo "<strong>$title</strong> - $authors<br>";
            echo "<p>$description</p>";
            echo "<p><strong>Date de publication :</strong> $publishedDate</p>";


         echo "<form action='add_favorites.php' method='POST'>";
            echo "<input type='hidden' name='title' value='$title'>";
            echo "<input type='hidden' name='authors' value='$authors'>";
            echo "<input type='hidden' name='thumbnail' value='$thumbnail'>";
            echo "<input type='hidden' name='book_id' value='$bookId'>";
            echo "<button type='submit'>Ajouter aux favoris</button>";
         echo "</form>";
    
            echo "</li>";
        }

        echo "</ul>";
    } elseif ($_SERVER["REQUEST_METHOD"] === "POST") { // Vérifie la méthode POST
        echo '<p class="no-results">Aucun livre trouvé.</p>';
    }
    ?>
</html