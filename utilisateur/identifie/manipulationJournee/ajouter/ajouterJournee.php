<?php
session_start();

if (!isset($_SESSION['id']))
    header("location: ../../../index.php");

else {
    $bdd = null;
    require_once '../../../../function/bdd.php';

    $id = $_SESSION['id'];
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF8"/>
        <title>Ajouter une journée</title>
        <style>
            @font-face {
                font-family: myFirstFont;
                src: url(../../../../font-style/ABeeZee-Regular.ttf);
            }
            body {
                font-family: myFirstFont;
                margin-top: 4%;
                text-align: center;
            }
            a {
                text-decoration: none;
            }

            .return_button {
                position: absolute;
                left: 4%;
                bottom: 10%;
                font-size: 300%;
                padding: 2% 4%;
                border: none;
                border-radius: 20px;
                color: white;
                background-color: #212F3D;
                /* background-color: #2E4053; */
            }

            form {
                font-size: 300%;
                margin-top: 30%;
            }

            form > div {
                margin-bottom: 30px;
            }

            input {
                font-size: 100%;
            }

            input[type='time'], input[type='date'] {
                border-radius: 20px;
            }

            input[type="submit"] {
                -webkit-appearance: none;
                margin-top: 16%;
                padding: 2% 8%;
                border: none;
                border-radius: 20px;
                color: white;
                background-color: #3B326C;
                box-shadow: #3B326C 0 60px 60px -40px;
            }
            input[type='checkbox'], input[type='radio'] {
                width: 10%;
                height: 75%;
            }

            <?php

        if(isset($_GET['error']) or isset($_GET['succes'])) {
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
            width: 90%;
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
            font-size: 300%;
        }

        .content > a {
            display: inline-block;
            background-color: #212F3D;
            color: white;
            text-decoration: none;
            padding: 2% 4%;
            border-radius: 20px;
        }
            <?php
        }
        ?>
        </style>
    </head>
    <body>
    <h1 style="font-size: 350%">Ajouter une journée</h1>

    <form action="ajouterJourneePost.php" method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr">
            <div style="display: flex; align-items: center; justify-content: flex-end">
                <label for="date" style="text-align: right; margin-right: 20px">Date :</label>
            </div>
            <div style="text-align: left">
                <input id="date" type="date" name="date" required value="<?php if(isset($_GET['date'])) echo $_GET['date']; else echo date('Y-m-d'); ?>">
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr">
            <div style="display: flex; align-items: center; justify-content: flex-end">
                <label for="hd" style="text-align: right; margin-right: 20px">Heure de début :</label>
            </div>
            <div style="text-align: left">
                <input id="hd" type="time" name="hDebut" required value="<?php if(isset($_GET['hDebut'])) echo $_GET['hDebut']; else echo date('H:i'); ?>">
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr">
            <div style="display: flex; align-items: center; justify-content: flex-end">
                <label for="hf" style="text-align: right; margin-right: 20px">Heure de fin :</label>
            </div>
            <div style="text-align: left">
                <input id="hf" type="time" name="hFin" required value="<?php if(isset($_GET['hFin'])) echo $_GET['hFin']; else echo date('H:i'); ?>">
            </div>
        </div>
        <?php
        if($bdd->query("SELECT idSociete FROM User WHERE id = '$id' AND idSociete IS NULL")->fetch()){
            ?>
            <div style="display: grid; grid-template-columns: 1fr 1fr">
                <div style="display: flex; align-items: center; justify-content: flex-end">
                    <label for="pause" style="text-align: right; margin-right: 20px">Pause :</label>
                </div>
                <div  style="text-align: left">
                    <input id="pause" type="time" name="coupure" required value="<?php if(isset($_GET['coupure'])) echo $_GET['coupure']; else echo '00:00:00'; ?>">
                </div>
            </div>
            <?php
        }
        ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr">
            <div style="display: flex; align-items: center; justify-content: flex-end">
                <label style="text-align: right; margin-right: 20px">Découche :</label>
            </div>
            <div style="text-align: left">
                <label for="oui">Oui </label><input id="oui" type="radio" name="decouche" value="oui" <?php if(isset($_GET['decouche']) and $_GET['decouche'] == 'oui') echo 'checked'; ?>>
                <label for="non">Non </label><input id="non" type="radio" name="decouche" value="non" <?php if(isset($_GET['decouche']) and $_GET['decouche'] == 'non') echo 'checked'; elseif (!isset($_GET['decouche'])) echo 'checked'; ?>>
            </div>
        </div>

        <input type="submit" value="Ajouter la journée">

    </form>

    <?php
    if(isset($_GET['error'])) {
        $messageError = "";

        if($_GET['error'] == 'date')
            $messageError = "Vous essayez d'ajouter une journée qui existe déjà. Veuillez cliquer sur le lien si vous souhaitez la modifier : <br><a href='../modifier/modifierJournee.php?idHoraire=" . $_GET['idHoraire'] . "'>Modifier la journée</a>";

        elseif($_GET['error'] == 'BDD')
            $messageError = "Nous n'avons pas réussi à ajouter la journée.<br><br>L'administrateur a été averti de cet incident !";

        elseif($_GET['error'] == 'BDD_Admin')
            $messageError = "Nous n'avons pas réussi à ajouter la journée.<br><br>L'administrateur n'a pas pu être averti de cet incident !";

        ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content">
                    <p>
                        <span style="font-weight: bold; color: red">Un problème est survenu</span><br>
                        <br>
                        <?= $messageError ?>
                    </p>
                    <a href="#" onclick="closePopup()">J'ai compris</a>
                </div>
            </div>
        </div>
        <?php
    }
    elseif(isset($_GET['succes'])) {
        ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content">
                    <p style="margin: 8% 0">
                        La journée a été ajoutée avec succes !
                    </p>
                    <a href="#" onclick="closePopup()">Parfait !</a>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <a href="../ajouterOuModifier.php" class="return_button">Retour</a>

    <script>
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
        }
    </script>
    </body>
    </html>

<?php }
?>