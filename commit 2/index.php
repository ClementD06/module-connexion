<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Module de connexion</title>
</head>
    <body>
        <?php
            // Vérifier si l'utilisateur est connecté
            session_start();

            // Vérifier si le formulaire a été soumis
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Récupérer les données du formulaire
                $login = $_POST["login"];
                $formPassword = $_POST["password"];

                // Vérifier si les champs sont remplis
                if (!empty($login) && !empty($formPassword)) {
                    // Connexion à la base de données
                    $servername = "localhost";
                    $username = "root";
                    $dbPassword = "Choupimolly8490.";
                    $dbname = "moduleconnexion";

                    $conn = new mysqli($servername, $username, $dbPassword, $dbname);

                    // Vérifier la connexion
                    if ($conn->connect_error) {
                        die("La connexion à la base de données a échoué : " . $conn->connect_error);
                    }

                    // Préparer et exécuter la requête de sélection
                    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE login = ? AND password = ?");
                    $stmt->bind_param("ss", $login, $formPassword);
                    $stmt->execute();

                    // Récupérer le résultat de la requête
                    $result = $stmt->get_result();

                    // Récupérer le prénom de l'utilisateur
                    if ($result->num_rows == 1) {
                        $row = $result->fetch_assoc();
                        $prenom = $row["prenom"];
                    }

                    // Vérifier si l'utilisateur existe
                    if ($result->num_rows == 1) {
                        // L'utilisateur est connecté, attribuer les variables de session
                        $_SESSION["logged_in"] = true;
                        $_SESSION["login"] = $login;
                        $_SESSION["prenom"] = $prenom;
                        $_SESSION["role"] = "admin"; // Remplacer "admin" par le rôle approprié

                        // Rediriger vers la page d'accueil ou faire d'autres actions
                        // ...
                        header("location: index.php");
                        exit();
                    } else {
                        $errorMessage = "Identifiants invalides";
                    }

                    // Fermer la connexion
                    $stmt->close();
                    $conn->close();
                } else {
                    $errorMessage = "Veuillez remplir tous les champs.";
                }
            }
        ?>
    <section class=entete>
        <nav class="navbar">
            <div class="logo">
                <img id="img_logo" src="logo_nav.png" alt="logo_module">
                <a href class="txt_logo">MonEspace</a>
            </div>
            <div class="nav_links">
                <ul>
                    <?php
                    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true && isset($_SESSION["prenom"])) {
                        echo '<li class="bonjour">Bonjour, ' . $_SESSION["prenom"] . '</li>';
                    }                    
                    ?>

                    <?php
                    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
                        echo '<li><a href="logout.php">Déconnexion</a></li>';
                    }
                    ?>

                    <?php 
                    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) { 
                        echo '<li><a href="profil.php">Mon profil</a></li>';
                    } else { 
                        echo '<li><a href="inscription.php">Créer un compte</a></li>';
                    }
                    ?>

                    <?php 
                    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true && isset($_SESSION["login"]) && $_SESSION["login"] === "admin") {
                        echo '<li><a href="admin.php">Admin Panel</a></li>';
                    } elseif (!isset($_SESSION["login"]) || $_SESSION["login"] !== "admin") {
                        echo '<li style="display: none;"><a href="admin.php">Admin Panel</a></li>';
                    }
                    ?>

                    <?php 
                    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] === false) { 
                        echo '<li><a href="connexion.php">Se connecter</a></li>';
                    } 
                    ?>

                    <li><a href="index.php">Accueil</a></li>
                </ul>
            </div>
            <img src="menu-btn.png" alt="" class="menu_hamburger">
        </nav>
    </section>

    <section class="section_1">
        <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) { ?>
            <div class="bloc_left_online">
                <article class="txt_home">
                    <h2>Bienvenue</h2>
                    <h5>Vous êtes connecté</h5> <br>
                <div class="bouton">
                    <button id="bouton_home"><a href="profil.php">Modifier mon profil</a></button>
                    <button id="bouton_home"><a href="profil.php">Mes projets</a></button>
                </div>
                </article>
            </div>
        <?php } else { ?>
            <div class="bloc_left">
                <article class="txt_home">
                    <h2>Bienvenue</h2> <br>
                    <h5>Veuillez vous connecter</h5>
                </article>
            </div>
            <div class="bloc_right">
                <div class="container_form">
                    <div class="formulaire">
                        <?php if (isset($errorMessage)) { ?>
                            <p><?php echo $errorMessage; ?></p>
                        <?php } ?>
                        
                        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <label id="txt_label" for="login">Identifiant</label>
                            <input id="label_home" type="text" name="login" required><br>

                            <label id="txt_label" for="password">Mot de passe</label>
                            <input id="label_home" type="password" name="password" required><br>

                            <input id="log" type="submit" value="Se connecter"> <br>
                            <a href="mdp_reset.php">Mot de passe oublié</a> 
                            
                        </form>
                        <?php 
                        if (!isset($_SESSION["logged_in"])) { 
                            echo '<div class="create_account">
                                <a href="inscription.php">Vous n\'avez pas encore de compte ? Créer un compte</a></div>';                                                       
                        }
                        ?>     
                    </div>
                </div>          
            </div>
        <?php } ?>
    </section>

    <section class="section_2">
        <div class="footer">
            <p>© COPYRIGHT 2022 - Tous Droits Réservés -All Rights Reserved</p>
        </div>
    </section>

    <script>
        const menuHamburger = document.querySelector(".menu_hamburger");
        const navLinks = document.querySelector(".nav_links");

        menuHamburger.addEventListener('click',()=>{
            navLinks.classList.toggle('mobile-menu');
        });
    </script>
    </body>
</html>
