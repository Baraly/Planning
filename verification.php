<?php

session_start();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF8"/>
    <title>Connexion planning</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <style>
        @font-face {
            font-family: myFirstFont;
            src: url(font-style/ABeeZee-Regular.ttf);
        }
        body {
            font-family: myFirstFont;
            margin: 2% 10% 0;
            text-align: center;
        }

        .smallFontSize {
            font-size: 20px;
        }

        .normalFontSize {
            font-size: 22px;
        }

        .mediumFontSize {
            font-size: 24px;
        }

        .largeFontSize {
            font-size: 26px;
        }

        h1 {
            margin-bottom: 10px;
        }

        p {
            margin: 0;
            padding: 0;
        }

        p > a {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 10px;
            text-decoration: none;
            background-color: #212F3D;
            color: white;
            margin: 10px;
            cursor: pointer;
        }

        form {
            margin-top: 10px;
        }

        input {
            border: none;
            border-bottom: 1px solid black;
            outline-width: 0;
            box-shadow: none;
            padding: 0 0;
            border-radius: 0;
        }

        input[type="tel"] {
            padding: 4px 6px;
            border: none;
            border-bottom: 1px gray solid;
            border-radius: 0;
            margin: 10px 0;
            display: inline-block;
            text-align: left;
        }

        input[type="submit"] {
            -webkit-appearance: none;
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            color: white;
            background-color: #3B326C;
            box-shadow: #3B326C 0 5px 5px -3px;
            cursor: pointer;
        }

        .marginBottom10 {
            margin-bottom: 40px;
        }

        <?php
        if(isset($_GET['error']) or isset($_GET['accepte']) or isset($_GET['refuse'])) {
        ?>
         .overlay {
             position: fixed;
             top: 0;
             bottom: 0;
             right: 0;
             left: 0;
             background-color: rgba(39, 55, 70, 0.9);
             z-index: 20;
         }
        .subOverlay {
            position: relative;
            height: 100%;
            width: 100%;
            text-align: center;
        }
        .content {
            width: 40%;
            position: fixed;
            z-index: 21;
            border-radius: 10px;
            padding: 20px;
            background-color: white;
            display: inline-block;
            left: 50%;
            top: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }
        .content > a {
            display: inline-block;
            background-color: #212F3D;
            color: white;
            text-decoration: none;
            padding: 4px 12px;
            border-radius: 10px;
            margin-top: 10px;
        }
        <?php } ?>

        @media screen and (max-width: 1000px) {
            body {
                margin-top: 2%;
            }

            .smallFontSize {
                font-size: 300%;
            }

            .normalFontSize {
                font-size: 325%;
            }

            .mediumFontSize {
                font-size: 350%
            }

            .largeFontSize {
                font-size: 375%
            }

            h1 {
                margin-bottom: 20%;
            }

            p > a {
                padding: 2% 5%;
                border-radius: 30px;
                margin: 3%;
            }

            form {
                margin-top: 10%;
            }

            input[type="tel"] {
                padding: 2% 1%;
                margin: 2% 0;
                width: 80%;
            }

            input[type="submit"] {
                width: 60%;
                margin-top: 10%;
                padding: 3% 0;
                border-radius: 20px;
                box-shadow: #3B326C 0 60px 60px -40px;
            }

            .marginBottom10 {
                margin-bottom: 10%;
            }

        <?php
        if(isset($_GET['error']) or isset($_GET['accepte']) or isset($_GET['refuse'])) {
        ?>
            .content {
                width: 90%;
                border-radius: 20px;
                padding: 30px 12px;
            }
            .content > a {
                padding: 2% 4%;
                border-radius: 20px;
                margin-top: 4%;
            }
        <?php } ?>
        }
</style>
</head>
<body>

<div style="margin-bottom: 20px">
    <h1 style="display: inline-block; padding: 0; margin: 0" class="mediumFontSize">Planning</h1>
    <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); margin: 0; padding: 0" class="normalFontSize">V??rifications du compte</p>
</div>

<?php

$bdd = null;
include_once 'function/bdd.php';

if(isset($_GET['verifierCompte']) and !empty($_GET['email']) and !empty($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    echo "<h1 class='largeFontSize'>V??rification de compte</h1>";

    if($bdd->query("SELECT email FROM User WHERE email = '$email' AND verifie = 1")->fetch()) {
        echo "<p class='normalFontSize'>Votre compte a d??j?? ??t?? v??rifi??, tout est en ordre !</p>";
    }
    elseif($bdd->query("SELECT nom, prenom FROM User WHERE email = '$email' AND cleSecurite = '$token'")->fetch()) {
        $newToken = rand(1111111111, 9999999999);
        $bdd->exec("UPDATE User SET verifie = 1 WHERE email = '$email'");
        $bdd->exec("UPDATE User SET cleSecurite = '$newToken' WHERE email = '$email'");
        echo "<p class='normalFontSize'>Votre compte vient d'??tre v??rifi??, tout est en ordre !</p>";
    }
    else {
        $message = "Une erreur est survenue lors de la v??rification d'un compte. E-mail : " . $email;
        $bdd->exec("INSERT INTO Evenement(type, description, important) VALUES ('Erreur v??rification compte', '$message', 1)");
        echo "<p class='normalFontSize'>Une erreur est survenue ! L'administrateur vient d'??tre averti de cet incident.</p>";
    }

    echo "<p class='normalFontSize'>Vous pouvez fermer cette page web.</p>";
} // ???

elseif (isset($_GET['changementCle']) and !empty($_GET['email'])) {
    $userInfo = $bdd->query("SELECT id FROM User WHERE email = '" . $_GET['email'] . "'")->fetch();

    if($donnes = $bdd->query("SELECT nom, prenom, id FROM User WHERE code = '" . $_POST['code'] . "' AND email <> '" . $_GET['email'] . "'")->fetch()) {
        $message = "Cet utilisateur a essay?? de changer sa cl?? personnelle avec la cl?? de <a style='color: royalblue; background-color: white; box-shadow: none; margin: 0; padding: 0' href='../utilisateur/index.php?idUser=" . $donnes['id'] . "'>" . strtoupper($donnes['nom']) . " " . $donnes['prenom'] . "</a>.";
        if($bdd->exec('INSERT INTO Evenement(idUser, type, description, important) VALUES("' . $userInfo['id'] . '", "Erreur changement cl?? personnelle", "' . $message . '", 1)'))
            header('location: verification.php?clePersonnelleOubliee&email=' . $_GET['email'] . '&token=' . $_GET['token'] . '&error=' . $_POST['code']);
        else
            echo "<h1 class='largeFontSize'>Une erreur est survenue, l'administrateur n'a pas pu ??tre mis au courant !</h1>";
    }
    else {
        $newToken = rand(1111111111, 9999999999);

        $bdd->exec("UPDATE User SET code = '" . $_POST['code'] . "' WHERE email = '" . $_GET['email'] . "'");
        $bdd->exec("UPDATE User SET cleSecurite = '$newToken' WHERE email = '" . $_GET['email'] . "'");
        if(isset($_SESSION['error'])) {
            $_SESSION['error'] = 0;
        }
        $bdd->exec("UPDATE BlockUser SET estBloque = -1  WHERE ipAdresse = '" . $_SERVER['HTTP_X_FORWARDED_FOR'] . "' AND estBloque = 1 AND nbTentative >= 5 AND CURDATE() <= ALL (SELECT DATE_ADD(datage, INTERVAL dureeBloquage DAY) FROM BlockUser WHERE ipAdresse = '" . $_SERVER['HTTP_X_FORWARDED_FOR'] . "')");

        $bdd->exec("INSERT INTO Evenement(idUser, type, description) VALUES(" . $userInfo['id'] . ", 'Changement cl?? personnelle', 'Cet utilisateur vient de changer sa cl?? personnelle.')");

    ?>
    <h1 class="largeFontSize">R??initialisation de la cl?? personnelle</h1>
    <p class="normalFontSize">Votre cl?? personnelle vient d'??tre mise ?? jour ! Cliquez sur ce lien pour revenir ?? la page
    d'accueil : <a href="utilisateur/index.php">Page d'authentification</a></p>
    <?php
    }
} // ???

elseif(isset($_GET['clePersonnelleOubliee']) and !empty($_GET['email']) and !empty($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $userInfo = $bdd->query("SELECT id FROM User WHERE email = '$email'")->fetch();

    if($bdd->query("SELECT email FROM User WHERE email = '$email' AND cleSecurite = '$token'")->fetch()) {
        if(isset($_GET['error'])) {
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p class="normalFontSize">La cl?? personnelle <span style="font-weight: bold"><?= $_GET['error'] ?></span> est malheureusement d??j??
                            utilis??e.<br><br>
                            Si vous rencontrez des difficult??s ?? vous connecter ?? votre compte, alors veuillez contacter le
                            support :
                            <a href="mailto:planning_contact@baraly.fr" style="background-color: white; color: royalblue">planning_contact@baraly.fr</a>
                        </p>
                        <a class="normalFontSize" href="#" onclick="closePopup()">J'ai compris</a>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <h1 class="largeFontSize">R??initialisation de la cl?? personnelle</h1>

        <form action="verification.php?email=<?= $_GET['email'] ?>&token=<?= $token ?>&changementCle" method="POST">
            <input class="mediumFontSize" type="tel" name="code" minlength="6" maxlength="6" placeholder="Nouvelle cl?? personnelle" required>
            <br>
            <input class="normalFontSize" type="submit" value="Changer">
        </form>
        <?php
    }
    else {
        $message = "Une erreur est survenue lors du changement de cl?? personnelle.";
        $bdd->exec("INSERT INTO Evenement(idUser, type, description, important) VALUES ('" . $userInfo['id'] . "', 'Erreur changement cl?? personnelle', '$message', 1)");
        echo "<p class='normalFontSize'>Une erreur est survenue ! L'administrateur vient d'??tre averti de cet incident.</p>";
    }
} // ???

elseif(isset($_GET['reactivationCompte']) and !empty($_GET['email']) and !empty($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $userInfo = $bdd->query("SELECT id FROM User WHERE email = '$email'")->fetch();

    if($bdd->query("SELECT email FROM User WHERE email = '$email' AND cleSecurite = '$token'")->fetch()) {
        $bdd->exec("UPDATE User SET desactiver = 0 WHERE email = '$email'");
        ?>
        <h1 class="largeFontSize">R??activation du compte Planning</h1>

        <p class="normalFontSize">
            C'est parfait, votre compte vient d'??tre r??activ?? !
            <br>
            Vous pouvez d??s ?? pr??sent fermer cette page web.
        </p>
        <?php
    }
    else {
        $message = "Une erreur est survenue lors du changement de cl?? personnelle.";
        $bdd->exec("INSERT INTO Evenement(idUser, type, description, important) VALUES ('" . $userInfo['id'] . "', 'Erreur changement cl?? personnelle', '$message', 1)");
        echo "<p class='normalFontSize'>Une erreur est survenue ! L'administrateur vient d'??tre averti de cet incident.</p>";
    }
} // ???

elseif(isset($_GET['preferenceEmail']) and !empty($_GET['email'])) {
    if(isset($_GET['accepte'])) {
        $bdd->exec("UPDATE User SET preferenceEmail = 1 WHERE email = '" . $_GET['email'] . "'");
        $userInfo = $bdd->query("SELECT id, preferenceEmail, genre FROM User WHERE email = '" . $_GET['email'] . "'")->fetch();
        ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content">
                    <p class="normalFontSize">
                        Vous voil?? <?php if($userInfo['genre'] == 'Mme' or $userInfo['genre'] == 'Mlle') echo "abonn??e"; else echo "abonn??"; ?> aux e-mails mensuels !
                    </p>
                    <a class="normalFontSize" href="#" onclick="closePopup()">OK parfait !</a>
                </div>
            </div>
        </div>
        <?php
    }

    if(isset($_GET['refuse'])) {
        $bdd->exec("UPDATE User SET preferenceEmail = 0 WHERE email = '" . $_GET['email'] . "'");
        $userInfo = $bdd->query("SELECT id, preferenceEmail, genre FROM User WHERE email = '" . $_GET['email'] . "'")->fetch();
        ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content">
                    <p class="normalFontSize">
                        Vous voil?? <?php if($userInfo['genre'] == 'Mme' or $userInfo['genre'] == 'Mlle') echo "d??sabonn??e"; else echo "d??sabonn??"; ?> des e-mails mensuels !
                    </p>
                    <a class="normalFontSize" href="#" onclick="closePopup()">OK parfait !</a>
                </div>
            </div>
        </div>
        <?php
    }

    $userInfo = $bdd->query("SELECT id, preferenceEmail, genre FROM User WHERE email = '" . $_GET['email'] . "'")->fetch();
    ?>
    <h1 class="largeFontSize marginBottom10">Envoie des e-mails automatiques en fin de mois</h1>
    <?php
    if((int)$userInfo['preferenceEmail'] == 0){
    ?>
        <p style="text-align: center" class="normalFontSize marginBottom10">
            Vous n'??tes actuellement pas <?php if($userInfo['genre'] == 'Mme' or $userInfo['genre'] == 'Mlle') echo "abonn??e"; else echo "abonn??"; ?> aux e-mails mensuels.
        </p>
        <p style="text-align: left" class="normalFontSize marginBottom10">
          <span style="font-style: italic">Mais qu'est-ce que c'est ?</span>
            <br><br>
            Les e-mails mensuels sont des mails qui sont envoy??s tous les 1er du mois.
            On y retrouve dedans un fichier PDF qui contient toutes les journ??es effectu??es le mois pr??c??dent.<br><br>
            Vous pourrez vous y d??sabonner ?? n'importe quel moment avec le lien qui se trouvera dans cet e-mail !
        </p>
        <p class="normalFontSize">
            <a href='verification.php?preferenceEmail&email=<?= $_GET['email'] ?>&accepte'>Je suis <?php if($userInfo['genre'] == 'Mme' or $userInfo['genre'] == 'Mlle') echo "partante"; else echo "partant"; ?> !</a>
        </p>
        <?php
    }
    else {
        ?>
        <p style="text-align: center" class="normalFontSize marginBottom10">
            Vous ??tes actuellement <?php if($userInfo['genre'] == 'Mme' or $userInfo['genre'] == 'Mlle') echo "abonn??e"; else echo "abonn??"; ?> aux e-mails mensuels.
        </p>
        <p style="text-align: left" class="normalFontSize marginBottom10">
            <span style="font-style: italic">Mais qu'est-ce que c'est ?</span>
            <br><br>
            Les e-mails mensuels sont des mails qui sont envoy??s tous les 1er du mois.
            On y retrouve dedans un fichier PDF qui contient toutes les journ??es effectu??es le mois pr??c??dent.
        </p>
        <p class="normalFontSize">
            <a href='verification.php?preferenceEmail&email=<?= $_GET['email'] ?>&refuse'>Je souhaite m'y d??sabonner !</a>
        </p>
        <?php
    }
} // ???

?>
<script>
    function closePopup() {
        document.getElementById("overlay").style.display = "none";
    }
</script>
</body>
</html>