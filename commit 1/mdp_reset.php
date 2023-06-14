<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_mdp_reset.css">
    <title>Récuperation de mot de passe</title>
</head>
    <body>
        <?php
        // Vérifier si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $login = $_POST["login"];

        // Effectuer les vérifications dans la base de données
        // Connectez-vous à la base de données et effectuez une requête pour vérifier si les informations sont correctes

        // Si les informations sont correctes, afficher le mot de passe de l'utilisateur
        // Sinon, afficher un message d'erreur

        // Exemple de requête pour vérifier les informations dans la base de données
        $servername = "localhost";
        $username = "root";
        $dbpassword = "Choupimolly8490.";
        $dbname = "moduleconnexion";

        $conn = new mysqli($servername, $username, $dbpassword, $dbname);

        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT password FROM utilisateurs WHERE nom = ? AND prenom = ? AND login = ?");
        $stmt->bind_param("sss", $nom, $prenom, $login);
        $stmt->execute();
        $stmt->bind_result($password);

        if ($stmt->fetch()) {
            echo "Votre mot de passe est : " . $password;
        } else {
            echo "Les informations fournies sont incorrectes.";
        }

        $stmt->close();
        $conn->close();
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


                        <li><a href="connexion.php">Se connecter</a>

                        <li><a href="index.php">Accueil</a>
                        </li>
                    </ul>
                </div>
                <img src="menu-btn.png" alt="" class="menu_hamburger">
            </nav>
        </section>

        <section class="section_1">
            <div class="accueil">
                <div class=formulaire_mdp>
                    <h2>Récuperation de mot de passe</h2>

                    <?php if (!empty($message)) { ?>
                        <div class="success-message"><?php echo $message; ?></div>
                    <?php } ?>

                    <form method="POST" action="mdp_reset.php">
                    <label for="nom">Nom :</label>
                    <input type="text" name="nom" required><br>

                    <label for="prenom">Prénom :</label>
                    <input type="text" name="prenom" required><br>

                    <label for="login">Login :</label>
                    <input type="text" name="login" required><br>

                    <input type="submit" value="Afficher le mot de passe">
                    </form>
                </div>   
            </div>    
        </section>

        <section class="section_2">
            <div class="footer">
                <p>© COPYRIGHT 2022 - Tous Droits Réservés -All Rights Reserved</p>
            </div>
        </section>
    </body>
</html>


