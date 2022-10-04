<?php

$id = $_GET['idUser'];

$bdd = null;
require_once '../../../../function/bdd.php';

if($bdd->exec("INSERT INTO Requete (idUser, type, message, dateReception) VALUES ('$id', '" . $_POST['type'] . "', '" . $_POST['message'] . "', CURDATE())")) {
    header('location: faireRequete.php?idUser=' . $id . '&vkey=' . $_GET['vkey'] . '&succes');
}
else {
    if($bdd->exec("INSERT INTO Evenement (idUser, type, description, important) VALUES ('$id', 'Erreur envoie requête', 'Cet utilisateur a essayé de faire une requête.', 1)"))
        header('location: faireRequete.php?idUser=' . $id . '&vkey=' . $_GET['vkey'] . '&type=' . $_POST['type'] . '&message=' . $_POST['message'] . '&error=BDD');
    else
        header('location: faireRequete.php?idUser=' . $id . '&vkey=' . $_GET['vkey'] . '&type=' . $_POST['type'] . '&message=' . $_POST['message'] . '&error=BDD_Admin');

}