<?php

session_start();

$bdd = null;
include_once '../../../../function/bdd.php';
include_once '../../../../function/fonctionHeures.php';

if (!isset($_SESSION['id']))
    header("location: ../../../historique.php");
else {

    $id = $_SESSION['id'];

    $idHoraire = -1;
    if(isset($_GET['idHoraire']))
        $idHoraire = $_GET['idHoraire'];

    // Commencer une journée
    if (isset($_GET['commencer'])) {

        if($bdd->exec("INSERT INTO Horaire (idUser, datage, hDebut) VALUES ('$id', CURDATE(), NOW())")) {
            header("location: ../../planning.php");
        }
        else {
            $message = "La journée de cet utilisateur n'a pas pu commencer.";
            $bdd->exec("INSERT INTO Evenement (idUser, type, description, important) VALUES ('$id', 'Erreur commencement journée', '$message', 1)");
            header("location: commencerJournee.php?error=commencer");
        }

    }

    // Mettre en pause
    elseif (isset($_GET['pause'])) {

        if($bdd->exec("INSERT INTO Pause (idUser, hDebut) VALUES ('$id', NOW())")) {
            header("location: commencerJournee.php");
        }
        else {
            $message = "La pause de cet utilisateur n'a pas pu commencer.";
            $bdd->exec("INSERT INTO Evenement (idUser, type, description, important) VALUES ('$id', 'Erreur pause', '$message', 1)");
            header("location: commencerJournee.php?error=pause");
        }
    }

    // Finir une pause
    elseif (isset($_GET['pauseFinir'])) {

        if($bdd->exec("UPDATE Pause SET hFin = NOW() WHERE idUser = '$id' AND hFin IS NULL")) {
            header("location: commencerJournee.php");
        }
        else {
            $message = "La pause de cet utilisateur n'a pas pu finir.";
            $bdd->exec("INSERT INTO Evenement (idUser, type, description, important) VALUES ('$id', 'Erreur pause', '$message', 1)");
            header("location: commencerJournee.php?error=pauseFinir");
        }

    }

    // Finir une journée
    elseif (isset($_GET['finir'])) {

        if($bdd->exec("UPDATE Horaire SET hFin = NOW() WHERE idHoraire = '$idHoraire'")) {

            $journee = $bdd->query("SELECT hDebut, hFin FROM Horaire WHERE idHoraire = '$idHoraire'")->fetch();

            $tempsTravailleSeconde = differenceHeuresEnSecondes($journee['hDebut'], $journee['hFin']);

            if($bdd->query("SELECT id FROM User WHERE idSociete IS NOT NULL AND id = '$id'")->fetch()) {

                $coupureInfo = $bdd->query("SELECT borneDebut, borneFin, temps FROM User, Coupure WHERE User.idSociete = Coupure.idSociete AND User.id = '$id'");

                while ($coupure = $coupureInfo->fetch()) {
                    if(getHeureEnSeconde($coupure['borneDebut']) <= $tempsTravailleSeconde and getHeureEnSeconde($coupure['borneFin']) >= $tempsTravailleSeconde) {
                        $bdd->exec("UPDATE Horaire SET coupure = '" . $coupure['temps'] . "' WHERE idHoraire = '$idHoraire'");
                    }
                }
            }
            else {
                $tempsTotalPauseEnSecondes = 0;

                $pauseUser = $bdd->query("SELECT hDebut, hFin FROM Pause WHERE idUser = '$id'");

                while($pause = $pauseUser->fetch()) {
                    $tempsTotalPauseEnSecondes += differenceHeuresEnSecondes($pause['hDebut'], $pause['hFin']);
                }

                $tempsTotalPauseHeure = getSecondeEnHeure($tempsTotalPauseEnSecondes);

                $bdd->exec("UPDATE Horaire SET coupure = '" . $tempsTotalPauseHeure . "' WHERE idHoraire = '$idHoraire'");
                $bdd->exec("DELETE FROM Pause WHERE idUser = '$id'");
            }
            header("location: commencerJournee.php");
        }
        else {
            $message = "La journée de cet utilisateur n'a pas pu finir.";
            $bdd->exec("INSERT INTO Evenement (idUser, type, description, important) VALUES ('$id', 'Erreur fin journée', '$message', 1)");
            header("location: commencerJournee.php?error=finir");
        }

    }

    // Se mettre en découchage
    elseif (isset($_GET['découchage'])) {
        if($bdd->exec("UPDATE Horaire SET decouchage = 1 WHERE idHoraire = '$idHoraire'")) {
            header("location: commencerJournee.php");
        }
        else {
            $message = "Cet utilisateur n'a pas pu se mettre en découchage.";
            $bdd->exec("INSERT INTO Evenement (idUser, type, description, important) VALUES ('$id', 'Erreur découchage', '$message', 1)");
            header("location: commencerJournee.php?error=découchage");
        }
    }

    // Enlever le découchage
    elseif (isset($_GET['découchageAnnuler'])) {
        if ($bdd->exec("UPDATE Horaire SET decouchage = 0 WHERE idHoraire = '$idHoraire'")) {
            header("location: commencerJournee.php");
        } else {
            $message = "Cet utilisateur n'a pas pu enlever son découchage.";
            $bdd->exec("INSERT INTO Evenement (idUser, type, description, 1) VALUES ('$id', 'Erreur découchage', '$message', 1)");
            header("location: commencerJournee.php?error=découchageAnnuler");
        }
    }

}