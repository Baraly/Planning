<?php

session_start();

$bdd = null;
require_once '../../../../function/bdd.php';
require_once '../../../../function/fonctionHeures.php';

$idHoraire = $_GET['idHoraire'];

$idUser = $bdd->query("SELECT idUser FROM Horaire WHERE idHoraire = '$idHoraire'")->fetch();

$bddOK = true;

if($bdd->query("SELECT idHoraire FROM Horaire WHERE idHoraire = '$idHoraire' AND hDebut <> '" . $_POST['hDebut'] . ":00'")->fetch())
    $bddOK &= $bdd->exec("UPDATE Horaire SET hDebut = '" . $_POST['hDebut'] . ":00' WHERE idHoraire = '$idHoraire'");

if($bdd->query("SELECT idHoraire FROM Horaire WHERE idHoraire = '$idHoraire' AND hFin IS NULL")->fetch())
    $bddOK &= $bdd->exec("UPDATE Horaire SET hFin = '" . $_POST['hFin'] . ":00' WHERE idHoraire = '$idHoraire'");
elseif($bdd->query("SELECT idHoraire FROM Horaire WHERE idHoraire = '$idHoraire' AND hFin <> '" . $_POST['hFin'] . ":00'")->fetch())
    $bddOK &= $bdd->exec("UPDATE Horaire SET hFin = '" . $_POST['hFin'] . ":00' WHERE idHoraire = '$idHoraire'");

if($_POST['coupure'] == 'automatique') {
    $listeCoupure = $bdd->query("SELECT borneDebut, borneFin, temps FROM User, Coupure WHERE User.id = '" . $idUser['idUser'] . "' AND User.idSociete = Coupure.idSociete");

    $tempsTravailleSeconde = differenceHeuresEnSecondes($_POST['hDebut'] . ':00', $_POST['hFin'] . ':00');

    while ($infoCoupure = $listeCoupure->fetch()) {
        if (getHeureEnSeconde($infoCoupure['borneDebut']) <= $tempsTravailleSeconde and getHeureEnSeconde($infoCoupure['borneFin']) >= $tempsTravailleSeconde) {
            if($bdd->query("SELECT coupure FROM Horaire WHERE idHoraire = '$idHoraire' AND coupure <> '" . $infoCoupure['temps'] . "'")->fetch())
                $bddOK &= $bdd->exec("UPDATE Horaire SET coupure = '" . $infoCoupure['temps'] . "' WHERE idHoraire = '$idHoraire'");
        }
    }
}
elseif($bdd->query("SELECT idHoraire FROM Horaire WHERE idHoraire = '$idHoraire' AND coupure <> '" . $_POST['coupure'] . "'")->fetch())
    $bddOK &= $bdd->exec("UPDATE Horaire SET coupure = '" . $_POST['coupure'] . "' WHERE idHoraire = '$idHoraire'");


if($bdd->query("SELECT idHoraire FROM Horaire WHERE idHoraire = '$idHoraire' AND decouchage <> '" . $_POST['decouche'] . "'")->fetch())
    $bddOK &= $bdd->exec("UPDATE Horaire SET decouchage = '" . $_POST['decouche'] . "' WHERE idHoraire = '$idHoraire'");

if($bddOK) {
    header("location: modifierJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "&idHoraire=" . $idHoraire . "&succes");
}
else {
    if($bdd->exec("INSERT INTO Evenement (idUser, type, description, important) VALUES ('" . $_SESSION['id'] . "', 'Erreur modification journée', 'Cet utilisateur n a pas pu modifier sa journée.', 1)"))
        header("location: modifierJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "&idHoraire=" . $idHoraire . "&error=BDD");
    else
        header("location: modifierJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "&idHoraire=" . $idHoraire . "&error=BDD_Admin");
}
