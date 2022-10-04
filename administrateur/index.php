<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion administrateur Planning</title>
    <style>
        @font-face {
            font-family: myFirstFont;
            src: url(../font-style/ABeeZee-Regular.ttf);
        }
        body {
            font-family: myFirstFont;
            margin: 10px 20px;
        }

        label {
            font-size: 26px;
        }
        input[type='tel'] {
            padding: 4px 8px;
            font-size: 26px;
            border: 1px solid black;
        }
        input[type='submit'] {
            font-size: 26px;
            background-color: royalblue;
            color: white;
            border-radius: 10px;
            box-shadow: none;
            border: none;
            padding: 4px 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php

    session_start();

    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
    include_once '../function/mail.php';

        if (!empty($_POST['codeAdmin']) and !empty($_SESSION['codeAdmin'])) {
            if ($_POST['codeAdmin'] == $_SESSION['codeAdmin']) {
                $_SESSION['adminAccess'] = true;
                if (!empty($_GET['url']))
                    header("location: " . $_GET['url']);
                else
                    header("location: accueil.php");
            }
            else
                header("location: accueil.php");
        }
        else {
            $code = rand(100000000000000, 999999999999999);

            $_SESSION['codeAdmin'] = $code;

            $messageMail = '
                <html>
                <head>
                    <style>
                        body {
                            margin: 10px 10px;
                        }
                        div {
                            text-align: center;
                        }
                        span {
                            padding: 10px 20px;
                            font-weight: bold;
                            border: 1px solid rgba(0, 0, 0, 0.25);
                            background-color: rgba(0, 0, 0, 0.1);
                            border-radius: 10px;
                            margin-top: 10px; 
                            text-decoration: none;
                        }
                    </style>
                </head>
                <body>
                    <h2>Bonjour Baptiste,</h2>
                    <div>
                        <p>
                            Veuillez trouver ci-joint votre code d\'accès à votre espace administrateur : 
                        </p>
                        <span> ' . $code . '</span> 
                    </div>
                    <p>Bien cordialement, <br>Le service informatique</p>
                </body>
                </html>
            ';

            //echo "<h1>Le code est : " . $code . "</h1>";

            if(!EnvoyerMail("baptiste.bronsin@outlook.com", "Bronsin", "Baptiste", "Connexion administrateur", $messageMail)) {
                echo "<h1>Une erreur est survenue lors de l'envoie du mail !</h1>";
            }
        }
    ?>

<div style="margin-bottom: 20px">
    <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
    <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
</div>
<div style="text-align: center; margin-top: 18%">
    <?php
    if(!empty($_GET['url'])) {
        echo "<form action='accueil.php?url=" . $_GET['url'] . "' method='POST'>";
    }
    else {
        echo "<form action='index.php' method='POST'>";
    }
    ?>
        <label for="code">Code d'accès : </label>
        <input id="code" type="tel" name="codeAdmin">
        <input type="submit" value="Me connecter">
    </form>
</div>
</body>
</html>