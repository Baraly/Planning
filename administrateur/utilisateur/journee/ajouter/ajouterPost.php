<?php

$bdd = null;
require_once '../../../../function/bdd.php';
include_once '../../../../function/fonctionHeures.php';


$idUser = $_GET['idUser'];

$jour = $_POST['date'];
$heureDebut = $_POST['hDebut'];
$heureFin = $_POST['hFin'];

$coupure = '00:00:00';

$coupure = $_POST['coupure'];

if ($_POST['decouchage'] == 'on')
    $decouche = 1;
else
    $decouche = 0;

if ($bdd->query("SELECT idHoraire FROM Horaire WHERE idUser = '$idUser' AND datage = '$jour' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle)")->fetch()) {
    header("location: ajouter.php?error=date&idUser=" . $idUser . "&date=" . $jour . "&hDebut=" . $heureDebut . "&hFin=" . $heureFin . "&coupure=" . $coupure . "&decouche=" . $decouche);
}
else {
    if($coupure == 'automatique') {
        $listeCoupure = $bdd->query("SELECT borneDebut, borneFin, temps FROM User, Coupure WHERE User.id = '$idUser' AND User.idSociete = Coupure.idSociete");

        $tempsTravailleSeconde = differenceHeuresEnSecondes($heureDebut, $heureFin);

        while ($infoCoupure = $listeCoupure->fetch()) {
            if (getHeureEnSeconde($infoCoupure['borneDebut']) <= $tempsTravailleSeconde and getHeureEnSeconde($infoCoupure['borneFin']) >= $tempsTravailleSeconde) {
                $coupure = $infoCoupure['temps'];
            }
        }
    }

    if ($bdd->exec("INSERT INTO Horaire (idUser, datage, hDebut, hFin, coupure, decouchage) VALUES ('$idUser', '$jour', '$heureDebut:00', '$heureFin:00', '$coupure', '$decouche')"))
        header("location: ajouter.php?idUser=" . $idUser . "&succes");
    else
        header("location: ajouter.php?error=BDD&idUser=" . $idUser . "&date=" . $jour . "&hDebut=" . $heureDebut . "&hFin=" . $heureFin . "&coupure=" . $coupure . "&decouche=" . $decouche);
}
