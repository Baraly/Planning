<?php

$bdd = null;

include_once '../../../function/bdd.php';

require '../../../PHPMailer/src/Exception.php';
require '../../../PHPMailer/src/PHPMailer.php';
require '../../../PHPMailer/src/SMTP.php';
include_once '../../../function/mail.php';

if ($bdd->query("SELECT * FROM User WHERE email = '" . $_POST['mail'] . "'")->fetch()) {
    header("location: ajoutUtilisateur.php?error=Email&nom=" . $_POST['nom'] . "&prenom=" . $_POST['prenom'] . "&mail=" . $_POST['mail'] . "&tel=" . $_POST['tel'] . "&genre=" . $_POST['genre'] . "&code=" . $_POST['code'] . "&societe=" . $_POST['societe']);
}

elseif ($bdd->query("SELECT * FROM User WHERE code = '" . $_POST['code'] . "'")->fetch()) {
    header("location: ajoutUtilisateur.php?error=Code&nom=" . $_POST['nom'] . "&prenom=" . $_POST['prenom'] . "&mail=" . $_POST['mail'] . "&tel=" . $_POST['tel'] . "&genre=" . $_POST['genre'] . "&code=" . $_POST['code'] . "&societe=" . $_POST['societe']);
}

else {
    $nom = strtolower($_POST['nom']);
    $nom = ucwords($nom);

    $prenom = strtolower($_POST['prenom']);
    $prenom = ucwords($prenom);

    $email = strtolower($_POST['mail']);

    $genre = $_POST['genre'];
    $code = $_POST['code'];
    $societe = $_POST['societe'];
    $telephone = $_POST['tel'];

    $cleSecurite = rand(1111111111, 9999999999);

    if ($societe != "null")
        $bbdOK = $bdd->exec("INSERT INTO User(`nom`, `prenom`, `email`, `telephone`, `genre`, `code`, `inscription`, `idSociete`, `cleSecurite`) VALUES ('$nom', '$prenom', '$email', '$telephone', '$genre', '$code', NOW(), '$societe', '$cleSecurite')");
    else
        $bbdOK = $bdd->exec("INSERT INTO User( `nom`, `prenom`, `email`, `telephone`, `genre`, `code`, `inscription`, `cleSecurite`) VALUES ('$nom', '$prenom', '$email', '$telephone', '$genre', '$code', NOW(), '$cleSecurite')");

    if ($bbdOK) {
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
            <h2>Bonjour $prenom,</h2>
            <p>
            Je suis heureux de vous compter parmis nos utilisateurs ! ^^<br>
            Le site <a href='https://baraly.fr/Planning' style='color:royalblue; text-decoration: none'>Planning</a> a pour vocation d'être une aide quotidienne aux chauffeurs routiers.
            Moi, le moderateur de ce site, compte garder ce site accessible au plus grand nombre de personnes possible.<br>
            C'est avec vos remarques et vos conseils qu'on gardera <span style='font-style: italic'>Planning</span> ouvert pendant très longtemps (en tout cas je l'espère).
            </p>  
            <p>
            Pour des questions de sécurites, pouvez-vous faire vérifier votre compte ?<br>
            C'est-à-dire me confirmer que cette adresse email est bien la vôtre en cliquant sur ce lien : 
            <a href='https://baraly.fr/Test/Planning/verification.php?verifierCompte&email=$email&token=$cleSecurite' style='color: royalblue; text-decoration: none'>Vérifier mon compte</a>.
            </p>
            <p>Bien cordialement,<br>Baptiste, le modérateur du site Planning</p>
        </body> 
        </html>
        ";

        EnvoyerMail($email, $nom, $prenom, "Bienvenu sur Planning", $message);

        header("location: ajoutUtilisateur.php?succes");
    }
    else {
        header("location: ajoutUtilisateur.php?error=BDD&nom=" . $_POST['nom'] . "&prenom=" . $_POST['prenom'] . "&mail=" . $_POST['mail'] . "&tel=" . $_POST['tel'] . "&genre=" . $_POST['genre'] . "&code=" . $_POST['code'] . "&societe=" . $_POST['societe']);
    }
}