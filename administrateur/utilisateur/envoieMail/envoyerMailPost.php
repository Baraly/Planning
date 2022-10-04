<?php

$bdd = null;
include_once '../../../function/bdd.php';

require '../../../PHPMailer/src/Exception.php';

require '../../../PHPMailer/src/PHPMailer.php';
require '../../../PHPMailer/src/SMTP.php';
include_once '../../../function/mail.php';

$idUser = $_GET['idUser'];

$sujet = $_POST['sujet'];

$message = str_replace("\r\n", "<br />", $_POST['message']);
$message = str_replace("\n\r", "<br />", $_POST['message']);
$message = str_replace("\n", "<br />", $_POST['message']);
$message = str_replace("\r", "<br />", $_POST['message']);

$messageFinal = "<html><p>" . $message . "</p><p>Bien cordialement,<br>Baptiste, le modérateur de <span style='font-style: italic'>Planning</span></p></html>";


$userInfo = $bdd->query("SELECT nom, prenom, email FROM User WHERE id = '$idUser'")->fetch();

if(EnvoyerMail($userInfo['email'], $userInfo['nom'], $userInfo['nom'], $sujet, $messageFinal))
    header("location: envoyerMail.php?idUser=" . $idUser . "&succes");
else
    header("location: envoyerMail.php?idUser=" . $idUser . "&error");