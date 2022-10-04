<?php

$bdd = null;

include_once '../../../../function/bdd.php';

$idUser = $_GET['idUser'];
$idHoraire = $_GET['idHoraire'];

if(isset($_GET['restaurer'])) {
    $infoHoraireSupp = $bdd->query("SELECT * FROM Horaire WHERE idHoraire = '$idHoraire'")->fetch();

    if(!$bdd->query("SELECT idHoraire FROM Horaire WHERE idHoraire <> '$idHoraire' AND datage = '" . $infoHoraireSupp['datage'] ."' AND idUser = '$idUser' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle)")->fetch()) {
        if($bdd->exec("DELETE FROM HorairePoubelle WHERE idHoraire = '$idHoraire'")) {
            header("location: supprimer.php?idUser=" . $idUser . "&idHoraire=" . $idHoraire . "&succes");
        }
        else {
            header("location: supprimer.php?idUser=" . $idUser . "&idHoraire=" . $idHoraire . "&error=BDD");
        }
    }
    else {
        header("location: supprimer.php?idUser=" . $idUser . "&idHoraire=" . $idHoraire . "&error=dateDoublon&date=" . $infoHoraireSupp['datage']);
    }
}
