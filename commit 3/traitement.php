<?php
// ...

// Vérifier si le fichier a été téléchargé sans erreur
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['photo']['tmp_name'];
    $fileName = $_FILES['photo']['name'];
    $fileSize = $_FILES['photo']['size'];
    $fileType = $_FILES['photo']['type'];

    // Déplacez le fichier téléchargé vers un emplacement souhaité sur le serveur
    $uploadDir = './photo_profil/';
    $uploadedFilePath = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $uploadedFilePath)) {
        // Le fichier a été correctement téléchargé et déplacé, vous pouvez effectuer d'autres actions ici
        // Par exemple, enregistrez le chemin du fichier dans la base de données pour l'utilisateur connecté
        
        // Affichez la photo de profil dans la page suivante en utilisant le chemin du fichier
        echo '<img src="' . $uploadedFilePath . '" alt="Photo de profil">';
    } else {
        // Une erreur s'est produite lors du déplacement du fichier téléchargé
        echo 'Une erreur s\'est produite lors du téléchargement du fichier.';
    }
}
// ...
?>