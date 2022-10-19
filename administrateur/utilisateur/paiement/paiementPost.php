<?php

$bdd = null;

include_once '../../../function/bdd.php';

$idUser = $_GET['idUser'];

if (isset($_POST['montant']) and isset($_POST['mois']) and isset($_POST['etat'])) {
    $montant = $_POST['montant'];
    $dureeMois = $_POST['mois'];
    $etat = $_POST['etat'];
    $dateDebut = date('Y-m-d');

    // Cas où il existe déjà un abonnement en cours
    if($donnes = $bdd->query("SELECT dateFinAbonnement AS finAbonnement FROM Paiement WHERE idUser = '$idUser' AND dateDebutAbonnement <= CURDATE() AND CURDATE() <= dateFinAbonnement")->fetch()) {
        $dateFinBDD = date('Y-m-d', strtotime($donnes['finAbonnement']));
        $dateDebut = date('Y-m-d', strtotime("$dateFinBDD + 1 day"));
        $dateFin = date('Y-m-d', strtotime("$dateDebut + $dureeMois months"));

        $bddOK = $bdd->exec("INSERT INTO Paiement (idUser, montant, dateDebutAbonnement, dateFinAbonnement, etat) VALUES ('$idUser', '$montant', '$dateDebut', '$dateFin', '$etat')");
    }
    else {
        $dateFin = date('Y-m-d', strtotime("$dateDebut + $dureeMois months"));

        $bddOK = $bdd->exec("INSERT INTO Paiement (idUser, montant, dateDebutAbonnement, dateFinAbonnement, etat) VALUES ('$idUser', '$montant', '$dateDebut', '$dateFin', '$etat')");
    }

    if($bddOK)
        header('location: ajoutPaiement.php?idUser=' . $idUser . "&dateDebutAbonnement=" . $dateDebut . "&succes");
    else
        header('location: ajoutPaiement.php?idUser=' . $idUser . "&error");
}

