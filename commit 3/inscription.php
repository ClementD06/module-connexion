<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_register.css">
    <title>Créer un compte</title>
</head>
    <body>
        <?php
        // Démarrer la session
        session_start();

        // Vérifier si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Récupérer les données du formulaire
            $login = $_POST["login"];
            $prenom = $_POST["prenom"];
            $nom = $_POST["nom"];
            $formPassword = $_POST["password"];
            $confirmPassword = $_POST["confirmPassword"];

            // Vérifier si les champs sont remplis
            if (!empty($login) && !empty($prenom) && !empty($nom) && !empty($formPassword) && !empty($confirmPassword)) {
                // Vérifier si les mots de passe correspondent
                if ($formPassword == $confirmPassword) {
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

                    // Préparer et exécuter la requête d'insertion
                    $stmt = $conn->prepare("INSERT INTO utilisateurs (login, prenom, nom, password) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $login, $prenom, $nom, $formPassword);
                    $stmt->execute();

                    // Fermer la connexion et rediriger vers la page de connexion
                    $stmt->close();
                    $conn->close();

                    header("Location: connexion.php");
                    exit();
                } else {
                    $errorMessage = "Les mots de passe ne correspondent pas.";
                }
            } else {
                $errorMessage = "Veuillez remplir tous les champs.";
            }
        }
        ?>
            <section class=entete>
                <nav class="navbar">
                    <div class="logo">
                        <img id="img_logo" src="logo_module_co.png" alt="logo_module">
                        <a href class="txt_logo">Module de connexion</a>
                    </div>
                    <div class="nav_links">
                        <ul>
                            <?php
                            if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
                                echo '<li>Bienvenue, vous êtes connecté.</li>';
                            }
                            ?>

                            <?php
                            if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
                                echo '<li><a href="logout.php">Déconnexion</a></li>';
                            }
                            ?>

                            <?php 
                            if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) { 
                                echo '<li><a href="profil.php">Modifier mon profil</a></li>';
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

                            
                            <li><a href="index.php">Accueil</a></li>
                        </ul>
                    </div>
                    <img src="menu-btn.png" alt="" class="menu_hamburger">
                </nav>
            </section>

                <section class="section_1">   
                            <div class="formulaire">
                                <?php if (isset($errorMessage)) { ?>
                                    <p><?php echo $errorMessage; ?></p>
                                <?php } ?>
                                <h1>Créer un compte</h1>
                                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                    <label id="txt_label"  for="login">Login:</label>
                                    <input id="label_home" type="text" name="login" required><br>

                                    <label id="txt_label"  for="prenom">Prénom:</label>
                                    <input id="label_home" type="text" name="prenom" required><br>

                                    <label id="txt_label"  for="nom">Nom:</label>
                                    <input id="label_home" type="text" name="nom" required><br>

                                    <label id="txt_label"  for="password">Mot de passe:</label>
                                    <input id="label_home" type="password" name="password" required><br>

                                    <label id="txt_label"  for="confirmPassword">Confirmer le mot de passe:</label>
                                    <input id="label_home" type="password" name="confirmPassword" required><br>

                                    <input id="log" type="submit" value="Créer mon compte">
                                </form>
                            </div>          
                </section>

                <section class="section_2">
                    <div class="footer"></div>
                </section>

        <script>
            const menuHamburger = document.querySelector(".menu_hamburger")
            const navLinks = document.querySelector(".nav_links")

            menuHamburger.addEventListener('click',()=>{
            navLinks.classList.toggle('mobile-menu')
            })
        </script>
    </body>
</html>
