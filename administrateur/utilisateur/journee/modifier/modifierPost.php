<?php

$bdd = null;
require_once '../../../../function/bdd.php';

$idHoraire = $_GET['idHoraire'];
$idUser = $_GET['idUser'];

$bddOK = true;

// On supprime une journée
if(isset($_GET['supprimer'])) {
    if($bdd->exec("INSERT INTO HorairePoubelle (idHoraire, dateSuppression) VALUES ('$idHoraire', DATE_ADD(CURDATE(), INTERVAL 30 DAY))"))
        header("location: modifier.php?idUser=" . $idUser . "&suppressionOK");
    else
        header("location: modifier.php?idHoraire=" . $idHoraire . "&idUser=" . $idUser . "&error=suppression");
}
// On modifie une journée
else {
    if($_POST['decouchage'] == 'on')
        $decouche = 1;
    else
        $decouche = 0;

    if($bdd->query("SELECT idHoraire FROM Horaire WHERE idHoraire = '$idHoraire' AND hDebut <> '" . $_POST['hDebut'] . ":00'")->fetch())
        $bddOK &= $bdd->exec("UPDATE Horaire SET hDebut = '" . $_POST['hDebut'] . ":00' WHERE idHoraire = '$idHoraire'");

    if($bdd->query("SELECT idHoraire FROM Horaire WHERE idHoraire = '$idHoraire' AND hFin <> '" . $_POST['hFin'] . ":00'")->fetch())
        $bddOK &= $bdd->exec("UPDATE Horaire SET hFin = '" . $_POST['hFin'] . ":00' WHERE idHoraire = '$idHoraire'");

    if($bdd->query("SELECT idHoraire FROM Horaire WHERE idHoraire = '$idHoraire' AND coupure <> '" . $_POST['coupure'] . "'")->fetch()) {
        if($_POST['coupure'] == 'automatique') {
            $listeCoupure = $bdd->query("SELECT borneDebut, borneFin, temps FROM User, Coupure WHERE User.id = '$idUser' AND User.idSociete = Coupure.idSociete");

            $tempsTravailleSeconde = differenceHeuresEnSecondes($_POST['hDebut'], $_POST['hFin']);

            while ($infoCoupure = $listeCoupure->fetch()) {
                if (getHeureEnSeconde($infoCoupure['borneDebut']) <= $tempsTravailleSeconde and getHeureEnSeconde($infoCoupure['borneFin']) >= $tempsTravailleSeconde) {
                    $bddOK &= $bdd->exec("UPDATE Horaire SET coupure = '" . $infoCoupure['temps'] . "' WHERE idHoraire = '$idHoraire'");
                }
            }
        }
        else
            $bddOK &= $bdd->exec("UPDATE Horaire SET coupure = '" . $_POST['coupure'] . "' WHERE idHoraire = '$idHoraire'");
    }

    if($bdd->query("SELECT idHoraire FROM Horaire WHERE idHoraire = '$idHoraire' AND decouchage <> '" . $decouche . "'")->fetch())
        $bddOK &= $bdd->exec("UPDATE Horaire SET decouchage = '" . $decouche . "' WHERE idHoraire = '$idHoraire'");

    if($bddOK)
        header("location: modifier.php?idUser=" . $idUser . "&idHoraire=" . $idHoraire . "&succes");
    else
        header("location: modifier.php?idUser=" . $idUser . "&idHoraire=" . $idHoraire . "&error=modifier");
}

