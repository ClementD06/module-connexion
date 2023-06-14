<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_admin.css">
    <title>Document</title>
</head>
<body>
    <?php
    // Vérifier si l'utilisateur est connecté en tant qu'admin
    session_start();
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true || $_SESSION["login"] !== "admin") {
        header("Location: connexion.php");
        exit();
    }

    // Récupérer les informations des utilisateurs depuis la base de données
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "Choupimolly8490.";
    $dbname = "moduleconnexion";

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM utilisateurs");
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin</title>
    </head>
    <body>
        <section class=entete>
            <nav class="navbar">
                <div class="logo">
                    <img id="img_logo" src="logo_nav.png" alt="logo_module">
                    <a id="centrage" href class="txt_logo">MonEspace</a>
                </div>
                <div class="nav_links">
                <ul>
                    <li>
                        <?php
                        if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
                            echo "Bonjour, vous êtes connecté.";
                        }
                        ?>
                    </li>
                        <?php
                        if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
                            echo '<li><a href="logout.php"Déconnexion></a></li>';
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

                    <li><a href="index.php">Accueil</a>
                </li>
            </ul>
                </div>
                <img src="menu-btn.png" alt="" class="menu_hamburger">
            </nav>
        </section>
        <section class="section_1">
                    <div class="table"> 
                        <style>
                            table {
                                border-collapse: collapse;
                            }
                        </style>
                        <h1>Admin Panel</h1>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Login</th>
                                <th>Prénom</th>
                                <th>Nom</th>
                                <th>Mot de passe</th>
                            </tr>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row["id"]; ?></td>
                                    <td><?php echo $row["login"]; ?></td>
                                    <td><?php echo $row["prenom"]; ?></td>
                                    <td><?php echo $row["nom"]; ?></td>
                                    <td><?php echo $row["password"]; ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>        
                </div>
        </section>
        <section class="section_2">
            <div class="footer">
                <p>© COPYRIGHT 2022 - Tous Droits Réservés -All Rights Reserved</p>
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

    <?php
    $stmt->close();
    $conn->close();
    ?>
