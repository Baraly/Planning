<?php

$bdd = null;
include_once '../../function/bdd.php';

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';
include_once '../../function/mail.php';

$email = strtolower($_POST['email']);

if($userInfo = $bdd->query("SELECT email, verifie, nom, prenom, cleSecurite, id FROM User WHERE email = '$email'")->fetch()) {
    if($userInfo['verifie'] == 1) {
        $message = "
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
            <h2>Bonjour " . $userInfo['prenom'] . ",</h2>
            <p>
            Veuillez cliquer sur le lien pour réinitialiser votre clé personnelle :
            <a href='https://baraly.fr/Test/Planning/verification.php?clePersonnelleOubliee&email=" . $email . "&token=" . $userInfo['cleSecurite'] . "'>Réinitialiser ma clé personnelle</a>.
            </p>
            <p>
            Si vous n'êtes pas l'auteur de cette demande, veuillez ne pas tenir compte de cet e-mail.
            </p>
            <p>Bien cordialement,<br>Le service informatique Planning</p>
        </body> 
        ";
        if(EnvoyerMail($email, $userInfo['nom'], $userInfo['prenom'], "Clé personnelle oubliée", $message)) {
            header("location: cleOubliee.php?status=succes");
        }
        else {
            echo "<h1 style='text-align: center'>Une erreur est survenue lors de l'envoie de l'email !</h1>";
        }
    }
    else {
        $message = "
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
            <h2>Bonjour " . $userInfo['prenom'] . ",</h2>
            <p>
            Veuillez cliquer sur le lien pour vérifier votre compte Planning :
            <a href='https://baraly.fr/Test/Planning/verification.php?verifierCompte&email=" . $email . "&token=" . $userInfo['cleSecurite'] . "'>Faire vérifier mon compte</a>.
            </p>
            <p>
            Si vous n'êtes pas l'auteur de cette demande, veuillez ne pas tenir compte de cet e-mail.
            </p>
            <p>Bien cordialement,<br>Le service informatique Planning</p>
        </body> ";
        if(EnvoyerMail($email, $userInfo['nom'], $userInfo['prenom'], "Vérification de compte", $message)) {
            header("location: cleOubliee.php?email=" . $email . "&status=nonVerifie");
        }
        else {
            $bdd->exec("INSERT INTO Evenement (idUser, type, description, important) VALUES ('" . $userInfo['id'] . "', 'Erreur vérification de compte', 'L envoie d un e-mail de vérification de compte a échoué.', 1)");
            echo "<h1 style='text-align: center'>Une erreur est survenue lors de l'envoie de l'email !</h1>";
        }
    }
}
else {
    header("location: cleOubliee.php?email=" . $email . "&status=error");
}