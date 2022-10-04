<?php

$bdd = null;
include_once '../../function/bdd.php';

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';
include_once '../../function/mail.php';

$nom = $_GET['nom'];
$prenom = $_GET['prenom'];
$email = $_GET['email'];
$genre = $_GET['genre'];
$code = $_GET['code'];
$societe = $_GET['societe'];

if ($info = $bdd->query("SELECT id, nom, prenom FROM User WHERE email = '$email'")->fetch()) {
    $message = "Un utilisateur a essayé de se créer un compte avec l'adresse email de <a style='color: royalblue; background-color: white; box-shadow: none; margin: 0; padding: 0' href='../utilisateur/index.php?idUser=" . $info['id'] . "'>" . strtoupper($info['nom']) . " " . $info['prenom'] . "</a>.";
    $bdd->exec('INSERT INTO Evenement(type, description, important) VALUES("Création de compte", "' . $message . '", 1)');
    header("location: inscription.php?error=email&nom=$nom&prenom=$prenom&email=$email&genre=$genre&code=$code&societe=$societe");
}
elseif ($info = $bdd->query("SELECT id, nom, prenom FROM User WHERE code = '$code'")->fetch()) {
    $message = "Un utilisateur a essayé de se créer un compte avec la clé personnelle de <a style='color: royalblue; background-color: white; box-shadow: none; margin: 0; padding: 0' href='../utilisateur/index.php?idUser=" . $info['id'] . "'>" . strtoupper($info['nom']) . " " . $info['prenom'] . "</a>.";
    $bdd->exec('INSERT INTO Evenement(type, description, important) VALUES("Création de compte", "' . $message . '", 1)');
    header("location: inscription.php?error=code&nom=$nom&prenom=$prenom&email=$email&genre=$genre&code=$code&societe=$societe");
}
else {
    $id = $bdd->query("SELECT count(*) AS n FROM User")->fetch();
    $idNumber = intval($id['n']) + 1;
    $nom = strtolower($nom);
    $nom = ucwords($nom);

    $prenom = strtolower($prenom);
    $prenom = ucwords($prenom);

    $email = strtolower($email);

    $cleSecurite = rand(1111111111, 9999999999);

    if ($genre == "rien")
        $genre = "";

    $bddOK = true;
    if ($societe != "autre")
        $bbdOK = $bdd->exec("INSERT INTO User(id, `nom`, `prenom`, `email`, `genre`, `code`, `inscription`, `idSociete`, cleSecurite) VALUES (" . $idNumber . ", '$nom', '$prenom', '$email', '$genre', '$code', NOW(), '$societe', '$cleSecurite')");
    else
        $bbdOK = $bdd->exec("INSERT INTO User(id, `nom`, `prenom`, `email`, `genre`, `code`, inscription, cleSecurite) VALUES (" . $idNumber . ", '$nom', '$prenom', '$email', '$genre', '$code', NOW(), '$cleSecurite')");

    if ($bddOK) {

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

        $message = "Un nouvel utilisateur s'est inscrit";
        $bdd->exec("INSERT INTO Evenement (idUser, type, description) VALUES ('$idNumber', 'Inscription', '$message' )");

        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Création de compte</title>
            <style>
                @font-face {
                    font-family: myFirstFont;
                    src: url(../../font-style/ABeeZee-Regular.ttf);
                }
                body {
                    font-family: myFirstFont;
                    text-align: center;
                }
                span {
                    font-family: sans-serif;
            </style>
        </head>
        <body>
            <p style='text-align: center; font-size: 350%; margin-top: 20%'>Et voilà <span style='font-weight: bold'><?= $prenom ?></span>, le compte vient d'être créé avec succès !<br>
            <?php

            if ($societe != "autre") {
                $infoSociete = $bdd->query("SELECT * FROM Societe WHERE id = '" . $societe . "'")->fetch();
                echo "Vous avez été associé à l'entreprise <span style='font-weight: bold'>" . $infoSociete['nomSociete'] . "</span>.<br>";
            }

            ?>
                N'oubliez pas que votre clé personnelle est <span style='font-weight: bold'><?= $code ?></span>.<br>
                Vous pouvez dès à présent vous connecter au site avec ce lien :<br> <a href='../index.php'>Authentification Planning</a><br><br>
            </p>
        </body>
        </html>
        <?php
    } else {
        $bdd->exec("INSERT INTO Evenement (type, description, important) VALUES ('Erreur création de compte', 'Un utilisateur n a pas pu se créer de compte.', 1)");
        echo "<p style='text-align: center; font-size: 350%; margin-top: 20%'>Un problème est survenu lors de la création de votre compte !<br>L'administrateur a été averti de cet incident.</p>";
    }

}

?>