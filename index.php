<?php
require_once "db.php"; 

// Vérifie que le form ait été soumis en POST et que le bouton de submit ait bien été cliqué
if (($_SERVER["REQUEST_METHOD"] === "POST") && isset($_POST["submit"])) {
    
    if (!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
    } else {
            $error = "Veuillez remplir tous les champs";
        } 

    $name = htmlspecialchars(trim($_POST['name'])); // Supprime les espaces et protège contre les attaques XSS
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Vérifie que l'email est bien un mail
    $password = $_POST['password'];
    
    // Vérifie si l'email est au bon format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email invalide";
        exit;
    }
    
    if (strlen($password) < 6) {
        echo "Le mot de passe doit contenir au moins 6 caractères";
        exit;
    }
    
    // On vérifie ensuite que le mail n'existe pas déjà en BDD
    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        // Préparation la requête avec des paramètres pour protéger contre les injections SQL
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        
        // Exécution de la requête avec les valeurs sécurisées
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password
        ]);
        
        echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header("Location: login.php");
    } catch (PDOException $e) {
        // Gère les erreurs si l'email est déja utilisé
        if ($e->getCode() == 23000) {
            echo "Cet email est déjà utilisé.";
        } else {
            echo "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    
<div class="outer-box">
        <div class="inner-box">
            <header class="signup-header">

            <h2>Inscription Ici !</h2>

            </header>

<main class="signup-body">

<form action="index.php" method="POST">
    <label for="name">Nom :</label>
    <input type="text" name="name" placeholder="Prénom" required><br>
    
    <label for="email">Email :</label>
    <input type="email" name="email" placeholder="E-mail" required><br>
    
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" placeholder="Mot de passe" required><br>
    
    <button class="inscriptionbtn" name="submit" type="submit">S'inscrire</button>
</form>

<footer class="signup-footer">
    <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a></p>
</footer>

</div>
        <div class="circle c1"></div>
        <div class="circle c2"></div>
    </div>

<style> 

@import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap');


*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body{
    font-family: 'Lato', sans-serif;
}

.outer-box{
    width: 100vw;
    height: 100vh;
    background: linear-gradient(to top left, #51e2f5, #edf7f6,rgb(245, 148, 174));

}
    
.inner-box{
    width: 400px;
    margin: 0 auto;
    position: relative;
    top: 40%;
    transform: translateY(-50%);
    padding: 20px 40px;
    background: linear-gradient(to top left, #51e2f5, #edf7f6,rgb(245, 148, 174)); 
    border-radius: 50px;
    box-shadow: 2px 2px 5px #2773a5 ;
    z-index: 2;
}

.signup-header h2{
    font-size: 2.5rem;
    color: #212121;
    text-align: center;
}

.signup-body {
    margin: 10px 0;
}

.signup-body label{
    display: block;
    font-weight: bold;
}

.signup-body input{
    width: 100%;
    padding: 10px;
    border: 2px solid #ccc;
    border-radius: 25px;
    font-size: 1rem;
    margin: 4px;
}

.inscriptionbtn {
    background-color: #4CAF50;
    color: white;
    font-size: 16px;
    font-weight: bold;
    padding: 12px 24px;
    margin-left: 100px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.inscriptionbtn:hover {
    background-color: #45a049;
    transform: scale(1.05);
}

.inscription-btn:active {
    background-color: #3e8e41;
    transform: scale(0.98);
}


.signup-footer p{
    color: #555;
    text-align: center;
}

.signup-footer a{
    color: #2773a5;
}

.circle{
    width: 200px;
    height: 200px;
    border-radius: 100px;
    background: linear-gradient(to top right, #ffffff33, #ffffffff);
    position: absolute;
}

.c1{
    top: 100px;
    left: 40px;

}

.c2{
    bottom: 200px;
    right: 50px;
}

</style>