<?php

$bdd = null;
include_once '../function/bdd.php';

// Suppression des horaires mises en poubelle depuis 30 jours

$listeIdHoraireSupprimer = $bdd->query("SELECT idHoraire FROM HorairePoubelle WHERE dateSuppression = CURDATE()");

$nbSuppresion = 0;

while($journee = $listeIdHoraireSupprimer->fetch()) {
    if($bdd->exec("DELETE FROM HorairePoubelle WHERE idHoraire = '" . $journee['idHoraire'] . "'") and $bdd->exec("DELETE FROM Horaire WHERE idHoraire = '" . $journee['idHoraire'] . "'"))
        $nbSuppresion++;
}

// Suppression des évènements de plus de 1 jour et qui ont été traité

$listeEvenementSupprimer = $bdd->query("SELECT id FROM Evenement WHERE CURDATE() = DATE_ADD(dateEvenement, INTERVAL 1 DAY) AND connaissance IS NOT NULL");

while($evenement = $listeEvenementSupprimer->fetch()) {
    if($bdd->exec("DELETE FROM Evenement WHERE id = '" . $evenement['id'] . "'"))
        $nbSuppresion++;
}

// Suppression des connexions de plus de 90 jours

$nbConnexionSupprime = $bdd->query("SELECT COUNT(*) AS nb FROM Connexion WHERE CURDATE() = DATE_ADD(DATE(dateConnexion), INTERVAL 90 DAY)")->fetch();

if($bdd->exec("DELETE FROM Connexion WHERE CURDATE() = DATE_ADD(DATE(dateConnexion), INTERVAL 90 DAY)"))
    $nbSuppresion += (int)$nbConnexionSupprime['nb'] ;


// Suppression des Informations qui ont été clôturé il y a 3 jours

$nbInformationSupprime = $bdd->query("SELECT COUNT(*) AS nb FROM MessageInfo WHERE CURDATE() = DATE_ADD(DATE(dateCloture), INTERVAL 3 DAY)")->fetch();
$idMessageInfoSupprime = $bdd->query("SELECT id FROM MessageInfo WHERE CURDATE() = DATE_ADD(DATE(dateCloture), INTERVAL 3 DAY)");

while($msgInfo = $idMessageInfoSupprime->fetch()) {
    if($bdd->exec("DELETE FROM LuMessageInfo WHERE idMessageInfo = '" . $msgInfo['id'] . "'"))
        $nbSuppresion ++;
}

if($bdd->exec("DELETE FROM MessageInfo WHERE CURDATE() = DATE_ADD(DATE(dateCloture), INTERVAL 3 DAY)"))
    $nbSuppresion += (int)$nbInformationSupprime['nb'] ;



// Conclusion
$type = "Automatisation de la suppression d'éléments";
if($nbSuppresion > 1)
    $bdd->exec("INSERT INTO Evenement (type, description) VALUES ('$type', '" . $nbSuppresion . " éléments ont été supprimés automatiquement de la base de données !')");
else
    $bdd->exec("INSERT INTO Evenement (type, description) VALUES ('$type', '" . $nbSuppresion . " élément a été supprimé automatiquement de la base de données !')");