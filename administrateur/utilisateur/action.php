<?php

session_start();

if (!isset($_SESSION['adminAccess']))
    header("location: ../index.php");
else {

    function getGenre($mot): string {
        if($mot == 'M' or $mot == 'Mr')
            return 'Monsieur ';
        elseif($mot == 'Mlle')
            return 'Mademoiselle ';
        elseif($mot == 'Mme')
            return 'Madame ';
        else
            return '';
    }

    $bdd = null;
    include_once '../../function/bdd.php';

    require '../../PHPMailer/src/Exception.php';

    require '../../PHPMailer/src/PHPMailer.php';
    require '../../PHPMailer/src/SMTP.php';
    include_once '../../function/mail.php';

    $idUser = $_GET['idUser'];

    $infoUser = $bdd->query("SELECT * FROM User WHERE id = '$idUser'")->fetch();

    $nom = getGenre($infoUser['genre']) .  $infoUser['nom'];

    if ($_GET['action'] == 'modifierCode') {
        $url = "https://baraly.fr/Test/Planning/verification.php?email=" . $infoUser['email'] . "&token=" . $infoUser['cleSecurite'] . "&clePersonnelleOubliee";
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
            <h2>Bonjour $nom,</h2>
            <p>
            Veuillez cliquer sur le lien pour réinitialiser votre clé personnelle :
            <a href='$url'>Réinitialiser ma clé personnelle</a>.
            </p>
            <p>
            Si vous n'êtes pas l'auteur de cette demande, veuillez ne pas tenir compte de cet e-mail.
            </p>
            <p>Bien cordialement,<br>Le service informatique Planning</p>
        </body>
        ";

        if(EnvoyerMail($infoUser['email'], $infoUser['nom'], $infoUser['prenom'], 'Réinitialisation de la clé personnelle', $message))
            header("location: index.php?idUser=" . $idUser . "&action=modifierCode&succes");
        else
            header("location: index.php?idUser=" . $idUser . "&action=modifierCode&error");
    }

    elseif ($_GET['action'] == 'debloquer') {
        $bdd->exec("UPDATE User SET bloquer = 0 WHERE id = '$idUser'");
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
            <h2>Bonjour $nom,</h2>
            <p>
            Nous sommes ravi de vous annoncer que votre compte <span>Planning</span> a été débloqué par notre équipe.
            <br>
            Vous pouvez dès à présent vous connecter à votre espace personnel : <a href='https://baraly.fr/Test/Planning'>Me connecter</a>
            </p>
            <p>Bien cordialement,<br>Le service informatique Planning</p>
        </body>
        ";

        if(EnvoyerMail($infoUser['email'], $infoUser['nom'], $infoUser['prenom'], 'Compte Planning débloqué', $message))
            header("location: index.php?idUser=" . $idUser . "&action=debloquer&succes");
        else
            header("location: index.php?idUser=" . $idUser . "&action=debloquer&error");
    }

    elseif ($_GET['action'] == 'bloquer') {
        $bdd->exec("UPDATE User SET bloquer = 1 WHERE id = '$idUser'");
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
            <h2>Bonjour $nom,</h2>
            <p>
            Nous sommes dans le regret de vous annoncer que votre compte <span>Planning</span> a été bloqué par notre équipe.
            <br>
            La durée de ce bloquage n'a pas été défini.
            </p>
            <p>Avec toutes nos excuses,<br>Le service informatique Planning</p>
        </body>
        ";

        if(EnvoyerMail($infoUser['email'], $infoUser['nom'], $infoUser['prenom'], 'Compte Planning bloqué', $message))
            header("location: index.php?idUser=" . $idUser . "&action=bloquer&succes");
        else
            header("location: index.php?idUser=" . $idUser . "&action=bloquer&error");
    }

    elseif ($_GET['action'] == 'reactiver') {
        $bdd->exec("UPDATE User SET desactiver = 0 WHERE id = '$idUser'");
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
            <h2>Bonjour $nom,</h2>
            <p>
            Nous sommes ravi de vous annoncer que votre compte <span>Planning</span> a été réactivé par notre équipe.
            <br>
            Vous pouvez dès à présent vous connecter à votre espace personnel : <a href='https://baraly.fr/Test/Planning'>Me connecter</a>
            </p>
            <p>Bien cordialement,<br>Le service informatique Planning</p>
        </body>
        ";

        if(EnvoyerMail($infoUser['email'], $infoUser['nom'], $infoUser['prenom'], 'Compte Planning réactivé', $message))
            header("location: index.php?idUser=" . $idUser . "&action=reactiver&succes");
        else
            header("location: index.php?idUser=" . $idUser . "&action=reactiver&error");
    }

    elseif ($_GET['action'] == 'desactiver') {
        $bdd->exec("UPDATE User SET desactiver = 1 WHERE id = '$idUser'");
        $url = "https://baraly.fr/Test/Planning/verification.php?email=" . $infoUser['email'] . "&token=" . $infoUser['cleSecurite'] . "&reactivationCompte";
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
            <h2>Bonjour $nom,</h2>
            <p>
            Nous sommes dans le regret de vous annoncer que votre compte <span>Planning</span> a été désactivé par notre équipe.
            <br>
            Ce pendant vous pouvez le réactiver à tout moment avec ce lien : <a href='$url'>Réactiver mon compte</a>
            </p>
            <p>Bien cordialement,<br>Le service informatique Planning</p>
        </body>
        ";

        if(EnvoyerMail($infoUser['email'], $infoUser['nom'], $infoUser['prenom'], 'Compte Planning désactivé', $message))
            header("location: index.php?idUser=" . $idUser . "&action=desactiver&succes");
        else
            header("location: index.php?idUser=" . $idUser . "&action=desactiver&error");
    }

    elseif ($_GET['action'] == 'verifier') {
        $url = "https://baraly.fr/Test/Planning/verification.php?email=" . $infoUser['email'] . "&token=" . $infoUser['cleSecurite'] . "&verifierCompte";
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
            <h2>Bonjour $nom,</h2>
            <p>
            Veuillez cliquer sur le lien suivant pour vérifier votre compte Planning :
            <a href='$url'>Faire vérifier mon compte</a>.
            </p>
            <p>Bien cordialement,<br>Le service informatique Planning</p>
        </body> ";

        if(EnvoyerMail($infoUser['email'], $infoUser['nom'], $infoUser['prenom'], 'Vérification de compte', $message))
            header("location: index.php?idUser=" . $idUser . "&action=verifier&succes");
        else
            header("location: index.php?idUser=" . $idUser . "&action=verifier&error");
    }
}