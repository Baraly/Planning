<?php session_start(); ?>
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
            src: url(../font-style/ABeeZee-Regular.ttf);
        }
        body {
            font-family: myFirstFont;
            margin-top: 10%;
            text-align: center;
        }

        form {
            margin-top: 70%;
        }

        input {
            height: 12%;
            width: 12%;
            margin: 0 1.5%;
            border: none;
            border-bottom: 1px solid black;
            font-size: 616%;
            text-align: center;
            outline-width: 0;
            box-shadow: none;
            padding: 0 0;
            border-radius: 0;
        }

        .couche1 {
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            background-color: rgba(39, 55, 70, 0.8);
            z-index: 20;
        }
        .couche2 {
            position: relative;
            height: 100%;
            width: 100%;
            text-align: center;
        }
        .couche3 {
            position: fixed;
            z-index: 21;
            border-radius: 20px;
            padding: 20px 12px;
            background-color: white;
            display: inline-block;
            left: 50%;
            top: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            font-size: 20px;
        }

        @media screen and (max-width: 1000px) {
            .couche1, .couche3 {
                display: none;
            }
        }
    </style>
</head>
<body>

<?php

$bdd = null;
include_once '../function/bdd.php';

if ((!empty($_POST['n1']) or (isset($_POST['n1']) and $_POST['n1'] == "0")) and (!empty($_POST['n2']) or (isset($_POST['n2']) and $_POST['n2'] == "0")) and (!empty($_POST['n3']) or (isset($_POST['n3']) and $_POST['n3'] == "0")) and (!empty($_POST['n4']) or (isset($_POST['n4']) and $_POST['n4'] == "0")) and (!empty($_POST['n5']) or (isset($_POST['n5']) and $_POST['n5'] == "0")) and (!empty($_POST['n6']) or (isset($_POST['n6']) and $_POST['n6'] == "0"))) {
    $code = $_POST['n1'] . $_POST['n2'] . $_POST['n3'] . $_POST['n4'] . $_POST['n5'] . $_POST['n6'];

    if ($donnees = $bdd->query("SELECT id FROM User WHERE code = '$code' AND bloquer = 0 AND desactiver = 0")->fetch()) {
        if(!$bdd->query("SELECT id FROM BlockUser WHERE ipAdresse = '" . $_SERVER['HTTP_X_FORWARDED_FOR'] . "' AND estBloque = 1 AND nbTentative >= 5 AND CURDATE() < ALL (SELECT DATE_ADD(datage, INTERVAL dureeBloquage DAY) FROM BlockUser WHERE ipAdresse = '" . $_SERVER['HTTP_X_FORWARDED_FOR'] . "')")->fetch()) {
            $_SESSION['id'] = $donnees['id'];
            $_SESSION['prenom'] = $donnees['prenom'];

            if (preg_match("/(iphone)/i", $_SERVER["HTTP_USER_AGENT"]))
                $bdd->exec("INSERT INTO Connexion (idUser, appareil) VALUES ('" . $donnees['id'] . "', 'iPhone')");

            elseif (preg_match("/(android)/i", $_SERVER["HTTP_USER_AGENT"]))
                $bdd->exec("INSERT INTO Connexion (idUser, appareil) VALUES ('" . $donnees['id'] . "', 'Android')");

            elseif (preg_match("/(mac)/i", $_SERVER["HTTP_USER_AGENT"]))
                $bdd->exec("INSERT INTO Connexion (idUser, appareil) VALUES ('" . $donnees['id'] . "', 'MacOS')");

            elseif (preg_match("/(windows)/i", $_SERVER["HTTP_USER_AGENT"]))
                $bdd->exec("INSERT INTO Connexion (idUser, appareil) VALUES ('" . $donnees['id'] . "', 'Windows')");

            elseif (preg_match("/(linux)/i", $_SERVER["HTTP_USER_AGENT"]))
                $bdd->exec("INSERT INTO Connexion (idUser, appareil) VALUES ('" . $donnees['id'] . "', 'Linux')");

            elseif (preg_match("/(ubuntu)/i", $_SERVER["HTTP_USER_AGENT"]))
                $bdd->exec("INSERT INTO Connexion (idUser, appareil) VALUES ('" . $donnees['id'] . "', 'Ubuntu')");

            elseif (preg_match("/(webos)/i", $_SERVER["HTTP_USER_AGENT"]))
                $bdd->exec("INSERT INTO Connexion (idUser, appareil) VALUES ('" . $donnees['id'] . "', 'Webos')");

            else {
                $bdd->exec("INSERT INTO Connexion (idUser, appareil) VALUES ('" . $donnees['id'] . "', 'Autre')");
            }

            header("location: identifie/planning.php");
        }
    }
    elseif ($bdd->query("SELECT id FROM User WHERE code <> '$code'")->fetch()) {
        if($infoBloquage = $bdd->query("SELECT id FROM BlockUser WHERE ipAdresse = '" . $_SERVER['HTTP_X_FORWARDED_FOR'] . "' AND CURDATE() < ALL (SELECT DATE_ADD(datage, INTERVAL dureeBloquage DAY) FROM BlockUser WHERE ipAdresse = '" . $_SERVER['HTTP_X_FORWARDED_FOR'] . "')")->fetch()) {
            $bdd->exec("UPDATE BlockUser SET nbTentative = nbTentative + 1, datage = CURDATE() WHERE id = '" . $infoBloquage['id'] . "'");
        }
        else {
            $bdd->exec("INSERT INTO BlockUser(ipAdresse) VALUES('" . $_SERVER['HTTP_X_FORWARDED_FOR'] . "')");
        }

    }
}

?>

<div class="couche1">
    <div class="couche2">
        <div class="couche3">
            <h2>Bonjour, veuillez utiliser un format de type <span style="font-style: italic">mobile</span> pour vous rendre ce site.<br>Merci 🙃</h2>
        </div>
    </div>
</div>

<h1 style="font-size: 375%">Authentification au planning</h1>
<form id="myform" action="index.php" method="post">
    <input name="n1" id="n1" type="tel" required min="0" max="9" maxlength="1" placeholder="0"/>
    <input name="n2" id="n2" type="tel" required min="0" max="9" maxlength="1" placeholder="0"
           onkeydown="myFunction(event, 'n1')" onclick="precedent(2)"/>
    <input name="n3" id="n3" type="tel" required min="0" max="9" maxlength="1" placeholder="0"
           onkeydown="myFunction(event, 'n2')" onclick="precedent(3)"/>
    <input name="n4" id="n4" type="tel" required min="0" max="9" maxlength="1" placeholder="0"
           onkeydown="myFunction(event, 'n3')" onclick="precedent(4)"/>
    <input name="n5" id="n5" type="tel" required min="0" max="9" maxlength="1" placeholder="0"
           onkeydown="myFunction(event, 'n4')" onclick="precedent(5)"/>
    <input name="n6" id="n6" type="tel" required min="0" max="9" maxlength="1" placeholder="0"
           onkeydown="myFunction(event, 'n5')" onclick="precedent(6)" oninput="loadForm()"/>
</form>
<div style="margin-top: 20%;">
    <a href="cleOubliee/cleOubliee.php" style="font-size: 300%; color: royalblue; text-decoration: none">J'ai oublié mon code...</a>
</div>
<div style="margin-top: 4%; font-size: 300%">
    Me créer un compte :
    <a href="inscription/inscription.php" style="color: royalblue; text-decoration: none">Inscription</a>
</div>

<script type="text/javascript">
    function myFunction(event, number) {
        if (event.which === 8) {
            let e = document.getElementById(number);
            setTimeout(function () {
                e.focus();
            }, 0);
        }
    }

    function precedent(number) {
        let e = document.getElementById('n' + number);
        if (number > 1 && e.value === '' && document.getElementById('n' + (number - 1)).value === '')
            precedent(number - 1);
        else {
            setTimeout(function () {
                e.focus();
            }, 0);
        }
    }

    $("input").bind("input", function () {
        var $this = $(this);
        setTimeout(function () {
            if ($this.val().length >= parseInt($this.attr("maxlength"), 10))
                $this.next("input").focus();
        }, 0);
    });

    function loadForm() {
        document.forms["myform"].submit();
    }
</script>
</body>
</html>