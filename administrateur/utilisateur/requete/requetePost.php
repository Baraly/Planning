<?php

$bdd = null;

include_once '../../../function/bdd.php';


$idRequete = $_GET['idRequete'];

$infoUser = $bdd->query("SELECT User.id AS USERID FROM User, Requete WHERE User.id = Requete.idUser AND Requete.id = '" . $idRequete . "'")->fetch();

if(isset($_GET['cloturer'])) {
    $bdd->exec("UPDATE Requete SET dateCloture = CURDATE() WHERE id = '$idRequete'");
}
else {

    $messageForm = str_replace("\r\n", "<br />", $_POST['message']);
    $messageForm = str_replace("\n\r", "<br />", $_POST['message']);
    $messageForm = str_replace("\n", "<br />", $_POST['message']);
    $messageForm = str_replace("\r", "<br />", $_POST['message']);

    $bdd->exec("INSERT INTO MessageRequete(idRequete, message) VALUES ('$idRequete', '$messageForm')");
}

header('location: infoRequete.php?idUser=' . $infoUser['USERID'] . '&idRequete=' . $idRequete);