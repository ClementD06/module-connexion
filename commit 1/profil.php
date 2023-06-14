<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profil.css">
    <title>Modifier mon profil</title>
</head>
    <body>
        <?php
        // Vérifier si l'utilisateur est connecté
        session_start();
        if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
            header("Location: connexion.php");
            exit();
        }

        // Récupérer les informations de l'utilisateur depuis la base de données
        $servername = "localhost";
        $dbUsername = "root";
        $dbPassword = "Choupimolly8490.";
        $dbname = "moduleconnexion";

        $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }

        // Récupérer les informations de l'utilisateur connecté
        $userLogin = $_SESSION["login"];

        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE login = ?");
        $stmt->bind_param("s", $userLogin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $prenom = $row["prenom"];
            $nom = $row["nom"];
            $password = $row["password"];
            $login = $row["login"];
        } else {
            echo "Erreur : utilisateur introuvable.";
            exit();
        }

        $stmt->close();
        $conn->close();

        // Traitement du formulaire de modification
        $message = ""; // Variable pour stocker le message à afficher

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Récupérer les nouvelles valeurs du formulaire
            $nouveauPrenom = $_POST["prenom"];
            $nouveauNom = $_POST["nom"];
            $nouveauPassword = $_POST["password"];
            $nouveauLogin = $_POST["login"];

            // Mettre à jour les informations de l'utilisateur dans la base de données
            $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

            // Vérifier la connexion
            if ($conn->connect_error) {
                die("La connexion à la base de données a échoué : " . $conn->connect_error);
            }

            $updateStmt = $conn->prepare("UPDATE utilisateurs SET prenom = ?, nom = ?, login = ?, password = ? WHERE login = ?");
            $updateStmt->bind_param("sssss", $nouveauPrenom, $nouveauNom, $nouveauLogin, $nouveauPassword, $userLogin);
            $updateStmt->execute();

            // Mettre à jour la valeur du login dans la session
            $_SESSION["login"] = $nouveauLogin;

            $message = "Les modifications ont été enregistrées avec succès."; // Définir le message de succès

            $updateStmt->close();
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
                            echo '<li>Bienvenue, ' . $prenom . '</li>';
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
                <div class=formulaire_profil>
                    <?php if (!empty($message)) { ?>
                        <div class="success-message"><?php echo $message; ?></div>
                    <?php } ?>

                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                        <h2>Modifier mon profil</h2><br>
                        <label id="txt_label" for="prenom">Prénom:</label>
                        <input id="label_home" type="text" name="prenom" value="<?php echo $prenom; ?>" required><br>

                        <label id="txt_label" for="nom">Nom:</label>
                        <input id="label_home" type="text" name="nom" value="<?php echo $nom; ?>" required><br>

                        <label id="txt_label" for="login">Login:</label>
                        <input id="label_home" type="text" name="login" value="<?php echo $login; ?>" required><br>

                        <label id="txt_label" for="password">Mot de passe:</label>
                        <input id="label_home" type="password" name="password" value="<?php echo $password; ?>" required><br>

                        <input id="log" type="submit" value="Enregistrer les modifications">
                    </form>
                </div>
        </section>
        <section class="section_2">
            <div class="footer">
            </div>
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
