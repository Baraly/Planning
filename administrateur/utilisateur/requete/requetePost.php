<?php

$bdd = null;

include_once '../../../function/bdd.php';


require '../../../PHPMailer/src/Exception.php';
require '../../../PHPMailer/src/PHPMailer.php';
require '../../../PHPMailer/src/SMTP.php';
include_once '../../../function/mail.php';

$idRequete = $_GET['idRequete'];

$infoUser = $bdd->query("SELECT email, nom, prenom, Requete.id AS ID, message, User.id AS USERID FROM User, Requete WHERE User.id = Requete.idUser AND Requete.id = '" . $idRequete . "'")->fetch();

$messageForm = str_replace("\r\n", "<br />", $_POST['message']);
$messageForm = str_replace("\n\r", "<br />", $_POST['message']);
$messageForm = str_replace("\n", "<br />", $_POST['message']);
$messageForm = str_replace("\r", "<br />", $_POST['message']);

$message = "
<html>
<head>
<style>
    body {
        margin: 10px;
    }
    span {
        font-style: italic;
    }
</style>
</head>
<body>
<p>
Une réponse a été apportée à votre requête (N° $idRequete).
</p>
<p>
Contexte : <br>
<span>". $infoUser['message'] . "</span>
</p>
<p>
Réponse de l'administrateur :<br>
<span>$messageForm</span>
</p>
<p>
Si cette réponse n'est pas suffisamment clair pour vous, n'hésitez pas à répondre à cet email. Votre réponse sera automatiquement délivrée à l'administrateur.
</p>
<p>Bien cordialement,<br>Le service informatique Planning</p>
</body>
</html>
";

if (EnvoyerMail($infoUser['email'], $infoUser['nom'], $infoUser['prenom'], "Réponse à votre requête", $message)){
    $bdd->exec("UPDATE Requete SET dateTraitement = NOW() WHERE id = '" . $idRequete . "'");
    header("location: infoRequete.php?idUser=" . $infoUser['USERID'] . "&idRequete=" . $idRequete);
}
else {
    echo "<h1>Une erreur est survenue lors de l'envoie du message</h1>";
}