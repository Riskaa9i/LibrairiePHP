<?php
session_start();
require_once "db.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupére les info de l'utilisateur
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; 
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email invalide";
        exit;
    }
    
    try {
        // Prépare la requête sql pour récupérer les info de l'utilisateur
        $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Récupére les résultats sous forme de tableau associatif
        
        // Vérifie si l'utilisateur existe et si le mot de passe correspond au mot de passe haché de la bdd
        if ($user && password_verify($password, $user['password'])) {
            // Stocke les informations de l'utilisateur dans la session pour maintenir la connexion
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            
            echo "Connexion réussie ! Bienvenue, " . $_SESSION['user_name'] . ".";
            // Redirection vers la homepage (page de recherche)
            header("Location: search.php");
            exit;
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la connexion : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>

<div class="outer-box">
        <div class="inner-box">
            <header class="signup-header">

            <h2>Connexion</h2>

            </header>

<main class="signup-body">


    <form action="login.php" method="POST">
        <label for="email">Email :</label>
        <input type="email" name="email" placeholder="E-mail" required><br> 
        
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        
        <button class="inscriptionbtn" type="submit">Se connecter</button>
    </form>

    <footer class="signup-footer">
    <p>Pas encore de compte ? <a href="index.php">Inscrivez-vous ici</a></p>
    </footer>

    </div>
        <div class="circle c1"></div>
        <div class="circle c2"></div>
    </div>
</body>

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
    margin-left: 90px;
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

</html>
