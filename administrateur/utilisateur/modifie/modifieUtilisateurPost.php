<?php

$bdd = null;

include_once '../../../function/bdd.php';

if ($bdd->query("SELECT * FROM User WHERE email = '" . $_POST['email'] . "' AND id <> '" . $_GET['idUser'] . "'")->fetch()) {
    header("location: modifieUtilisateur.php?idUser=" . $_GET['idUser'] . "&error=Email");
}

else {
    $nom = strtolower($_POST['nom']);
    $nom = ucwords($nom);

    $prenom = strtolower($_POST['prenom']);
    $prenom = ucwords($prenom);

    $email = strtolower($_POST['email']);

    $genre = $_POST['genre'];
    $societe = $_POST['societe'];
    $telephone = $_POST['tel'];

    if (!$bdd->query("SELECT email FROM User WHERE id = '" . $_GET['idUser'] . "' AND email = '" . $email . "'")->fetch()) {
        $bdd->exec("UPDATE User SET verifie = 0 WHERE id = '" . $_GET['idUser'] . "'");
    }

    if ($societe != "null")
        $bbdOK = $bdd->exec("UPDATE User SET nom = '" . $nom . "', prenom = '" . $prenom . "', email = '" . $email . "', telephone = '" . $telephone . "', genre = '" . $genre . "', idSociete = '" . $societe . "', preferenceEmail = '" . $_POST['preferences'] . "', ancienPlanning = '" . $_POST['desing'] . "' WHERE id = '" . $_GET['idUser'] . "'");
    else
        $bbdOK = $bdd->exec("UPDATE User SET nom = '" . $nom . "', prenom = '" . $prenom . "', email = '" . $email . "', telephone = '" . $telephone . "', genre = '" . $genre . "', idSociete = null, preferenceEmail = '" . $_POST['preferences'] . "', ancienPlanning = '" . $_POST['desing'] . "' WHERE id = '" . $_GET['idUser'] . "'");

    if ($bbdOK) {
        header("location: ../index.php?idUser=" . $_GET['idUser']);
    }
    else {
        header("location: modifieUtilisateur.php?idUser=" . $_GET['idUser'] . "&error=BDD");
    }
}