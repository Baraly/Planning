<?php

session_start();

$bdd = null;
require_once '../../../../function/bdd.php';
include_once '../../../../function/fonctionHeures.php';

if (!isset($_SESSION['id']))
    header("location: ../../../historique.php");
else {

    $id = $_SESSION['id'];

    $jour = $_POST['date'];
    $heureDebut = $_POST['hDebut'];
    $heureFin = $_POST['hFin'];

    $coupure = '00:00:00';

    if (isset($_POST['coupure']))
        $coupure = $_POST['coupure'];

    if ($_POST['decouche'] == 'oui')
        $decouche = 1;
    else
        $decouche = 0;

    if ($donnees = $bdd->query("SELECT idHoraire FROM Horaire WHERE idUser = '$id' AND datage = '$jour' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle)")->fetch()) {
        header("location: ajouterJournee.php?error=date&idHoraire=" . $donnees['idHoraire'] . "&date=" . $jour . "&hDebut=" . $heureDebut . "&hFin=" . $heureFin . "&coupure=" . $coupure . "&decouche=" . $_POST['decouche']);
    } else {
        if ($bdd->query("SELECT idSociete FROM User WHERE id = '$id' AND idSociete IS NOT NULL")->fetch()) {
            $listeCoupure = $bdd->query("SELECT borneDebut, borneFin, temps FROM User, Coupure WHERE User.idSociete = Coupure.idSociete AND User.id = '$id'");

            $tempsTravailleSeconde = differenceHeuresEnSecondes($heureDebut, $heureFin);

            while ($infoCoupure = $listeCoupure->fetch()) {
                if (getHeureEnSeconde($infoCoupure['borneDebut']) <= $tempsTravailleSeconde and getHeureEnSeconde($infoCoupure['borneFin']) >= $tempsTravailleSeconde) {
                    $coupure = $infoCoupure['temps'];
                }
            }
        }

        if ($bdd->exec("INSERT INTO Horaire (idUser, datage, hDebut, hFin, coupure, decouchage) VALUES ('$id', '$jour', '$heureDebut:00', '$heureFin:00', '$coupure', '$decouche')"))
            header("location: ajouterJournee.php?succes");
        else {
            if ($bdd->exec("INSERT INTO Evenement (idUser, type, description, important) VALUES ('$id', 'Erreur ajouter une journée', 'Cet utilisateur n a pas réussi à ajouter une journée', 1)"))
                header("location: ajouterJournee.php?error=BDD&date=" . $jour . "&hDebut=" . $heureDebut . "&hFin=" . $heureFin . "&coupure=" . $coupure . "&decouche=" . $_POST['decouche']);
            else
                header("location: ajouterJournee.php?error=BDD_Admin&date=" . $jour . "&hDebut=" . $heureDebut . "&hFin=" . $heureFin . "&coupure=" . $coupure . "&decouche=" . $_POST['decouche']);
        }
    }
}