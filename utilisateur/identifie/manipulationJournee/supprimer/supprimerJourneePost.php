<?php

session_start();

$bdd = null;
require_once '../../../../function/bdd.php';

$id = $_SESSION['id'];

$idHoraire = $_GET['idHoraire'];

// On veut supprimer la journée
if(isset($_GET['supprimer'])) {
    // Si l'idHoraire appartient bien à cet utilisateur
    if($bdd->query("SELECT idHoraire FROM Horaire WHERE idUser = '$id' AND idHoraire = '$idHoraire'")->fetch()) {
        // Si on arrive à placer l'idHoraire dans la table HorairePoubelle
        if($bdd->exec("INSERT INTO HorairePoubelle (idHoraire, dateSuppression) VALUES ('$idHoraire', DATE_ADD(CURDATE(), INTERVAL 30 DAY))")) {
            header("location: ../modifier/modifierJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "&succesDelete");
        }
        // Si on n'arrive pas à placer l'idHoraire dans la table HorairePoubelle
        else {
            // On previent l'administrateur du problème
            if($bdd->exec("INSERT INTO Evenement (idUser, type, description, important) VALUES ('$id', 'Erreur insertion HorairePoubelle', 'Cet utilisateur n a pas pu \"supprimer\" sa journée.', 1)")) {
                header("location: ../modifier/modifierJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "&idHoraire=" . $idHoraire . "&error=BDD_suppression");
            }
            else {
                header("location: ../modifier/modifierJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "&idHoraire=" . $idHoraire . "&error=BDD_suppression_admin");
            }
        }
    }
// Si l'idHoraire n'appartient pas à cet utilisateur
    else {
        header("location: ../modifierJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "&idHoraire=" . $idHoraire . "&error=idHorairePasUser");
    }
}

// On veut restaurer la journée
elseif(isset($_GET['restaurer'])) {
    $infoHoraireSupprimee = $bdd->query("SELECT datage FROM Horaire WHERE idHoraire = '$idHoraire'")->fetch();

    // Si on n'a pas de journée à cette même date
    if(!$bdd->query("SELECT idHoraire FROM Horaire WHERE idUser = '$id' AND idHoraire <> '$idHoraire' AND datage = '" . $infoHoraireSupprimee['datage'] . "' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle)")->fetch()) {
        // Si on arrive à enlever l'idHoraire de la table HorairePoubelle
        if($bdd->exec("DELETE FROM HorairePoubelle WHERE idHoraire = '$idHoraire'")) {
            header("location: supprimerJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "&succesDelete");
        }
        // Si on n'arrive pas à enlever l'idHoraire de la table HorairePoubelle
        else {
            // On previent l'administrateur du problème
            if($bdd->exec("INSERT INTO Evenement (idUser, type, description, important) VALUES ('$id', 'Erreur suppression de HorairePoubelle', 'Cet utilisateur n a pas pu restaurer sa journée.', 1)")) {
                header("location: supprimerJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "&error=BDD_suppression");
            }
            else {
                header("location: supprimerJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "&error=BDD_suppression_admin");
            }
        }
    }
    // Si on a déjà une journée à cette même date
    else {
        header("location: supprimerJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "&error=dejaJournee&date=" . date('Y-m-d', strtotime($infoHoraireSupprimee['datage'])));
    }
}
