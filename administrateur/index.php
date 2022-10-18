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
        input[type='text'] {
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

        if (isset($_SESSION['codeAdmin']) and $_SESSION['codeAdmin'])
            header("location: accueil.php");
        elseif (!empty($_POST['codeAdmin'])) {
            if ($_POST['codeAdmin'] == "b3abbaad57a231464cc2ef045f084d2def3ba4169471c1787aebf210a61aa4eed7b9ec7e2829e2e4a924de2b6cbfe98906616fd7b38c7b64ae3795d1f6fdde3679a7c72c471fabd6377d552d9222d3b6") {
                $_SESSION['adminAccess'] = true;
                if (!empty($_GET['url']))
                    header("location: " . $_GET['url']);
                else
                    header("location: accueil.php");
            }
            else
                header("location: index.php");
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
        <input id="code" type="text" name="codeAdmin" value="<?php if(!empty($_GET['codeAdmin'])) echo $_GET['codeAdmin']; ?>">
        <input type="submit" value="Me connecter">
    </form>
</div>
</body>
</html>