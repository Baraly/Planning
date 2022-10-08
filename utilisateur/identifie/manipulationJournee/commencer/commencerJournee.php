<?php
session_start();

if (!isset($_SESSION['id']))
    header("location: ../../../index.php");
else {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF8"/>
        <title>Commencer une journée</title>
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

            .button {
                display: inline-block;
                -webkit-appearance: none;
                font-size: 325%;
                padding: 3% 10%;
                border: none;
                border-radius: 20px;
                color: white;
                background-color: #212F3D;
                /* background-color: #2E4053; */
                box-shadow: #2E4053 0 60px 60px -40px;
            }

            .button:active {
                box-shadow: none;
            }

            .button_light {
                display: inline-block;
                font-size: 300%;
                padding: 3% 8%;
                border-radius: 20px;
                color: #212F3D;
                border: 1px solid #212F3D;
                background-color: #ECECEB;
                /* background-color: #2E4053; */
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

            <?php

        if(isset($_GET['error'])) {
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

            .settingLayer {
                position: absolute;
                display: inline-block;
                top: 3%;
                right: 2%;
                border-radius: 20px;
                background-color: #212F3D;
                width: 130px;
                height: 130px;
            }

            .settingWhite {
                position: relative;
                width: 130px;
                height: 130px;
            }

            .settingWhite > img {
                display: inline-block;
                position: absolute;
                width: 90px;
                height: 90px;
                border-radius: 16px;
                left: 50%;
                top: 50%;
                -ms-transform: translate(-50%, -50%);
                transform: translate(-50%, -50%);
            }
        </style>
    </head>
    <body>
    <h1 style="font-size: 375%">Menu</h1>

    <!--
    <a href="../../parametres/index.php" class="settingLayer">
        <div class="settingWhite">
            <img src="../../images/cog-solid-36.png" alt="setting">
        </div>
    </a>
    -->

    <?php
    $bdd = null;
    include_once '../../../../function/bdd.php';
    include_once '../../../../function/fonctionHeures.php';


    date_default_timezone_set('Europe/Paris');

    $idHoraire = 0;

    $debut = null;
    $fin = null;
    $decouchage = false;
    $peutMettreEnPause = false;
    $estEnPause = false;
    $journeeFinie = false;

    // Une journée est en cours
    if($donnees = $bdd->query('SELECT idHoraire, hDebut, hFin, datage FROM Horaire WHERE idUser = "' . $_SESSION['id'] . '" AND (datage = CURDATE() OR datage = DATE_SUB(CURDATE(), INTERVAL 1 DAY)) AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle) ORDER BY datage DESC LIMIT 1')->fetch()) {
        $debut = $donnees['hDebut'];
        $fin = $donnees['hFin'];
        $idHoraire = $donnees['idHoraire'];

        if(tempsEntreDeuxDateEtHeure(($donnees['datage'] . ' ' . $donnees['hDebut']), date('Y-m-d H:i:s')) > 20 * 3600)
            $journeeFinie = true;

        //if (differenceHeuresEnSecondes($donnees['hDebut'], date('H:i:s')) > 20 * 3600)
            //$journeeFinie = true;
    }

    // Peut mettre en pause
    if($bdd->query("SELECT id FROM User WHERE id = '" . $_SESSION['id'] . "' AND idSociete IS NULL")->fetch()) {
        $peutMettreEnPause = true;
    }

    // Est actuellement en pause
    if($donnees = $bdd->query("SELECT hDebut FROM Pause WHERE idUser = '" . $_SESSION['id'] . "' AND hFin IS NULL")->fetch()) {
        $estEnPause = true;
    }

    // Être en découchage
    if($bdd->query("SELECT idHoraire FROM Horaire WHERE decouchage = 1 AND idHoraire = '" . $idHoraire . "'")->fetch()) {
        $decouchage = true;
    }

    if(!$debut and !$fin or $journeeFinie) {
        ?>
        <p style="font-size: 325%">La journée n'a pas encore commencé</p>
        <?php
    }
    elseif(!$fin) {
        ?>
        <p style="font-size: 325%">La journée a déjà commencé</p>
        <?php
    }
    else {

        if($infoMessage = $bdd->query("SELECT id, message FROM MessageInfo JOIN LuMessageInfo ON MessageInfo.id = idMessageInfo WHERE idUser = '" . $_SESSION['id'] . "' AND dateLecture IS NULL AND (dateCloture IS NULL OR DATEDIFF(dateCloture, CURDATE()) > 0)")->fetch()) {
            ?>
                <style>
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
                        position: absolute;
                        z-index: 21;
                        border-radius: 20px;
                        padding: 1% 6%;
                        background-color: white;
                        display: inline-block;
                        right: 8%;
                        left: 8%;
                        top: 18%;
                        bottom: 18%;
                        font-size: 300%;
                        text-align: left;
                        overflow: auto;
                    }
                    .content a {
                        display: inline-block;
                        background-color: #212F3D;
                        color: white;
                        text-decoration: none;
                        padding: 2% 4%;
                        border-radius: 20px;
                        margin-bottom: 20px;
                    }
                    hr {
                        margin: 20px 0;
                    }
                </style>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            <div style="text-align: center">
                                <span style="font-weight: bold; color: royalblue">-- Information --</span>
                            </div>
                            <hr>
                            <?= $infoMessage['message'] ?>
                        </p>
                        <div style="text-align: center">
                            <a href="../lireMessageInfo.php?updateInformation=<?= $infoMessage['id'] ?>">J'ai compris</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <p style="font-size: 325%">La journée est finie</p>
        <?php
    }
    ?>

    <div style="width: 60%; margin: 0 auto; box-shadow: 0 0 10px 10px rgba(33, 47, 61, 0.2); border-radius: 50px">
        <div style="font-size: 325%; display: grid; grid-template-rows: 1fr 1fr">
            <div style="display: grid; grid-template-columns: 1fr 1fr">
                <p style="margin: 20px 10px; padding: 0; text-align: right">Début : </p>
                <p style="margin: 20px 10px; padding: 0; text-align: left"><?php if($debut and !$journeeFinie) echo date('H:i', strtotime($debut)); ?></p>
            </div>
            <div  style="display: grid; grid-template-columns: 1fr 1fr">
                <p style="margin: 20px 10px; padding: 0; text-align: right">Fin : </p>
                <p style="margin: 20px 10px; padding: 0; text-align: left"><?php if($fin and !$journeeFinie) echo date('H:i', strtotime($fin)); ?></p>
            </div>
        </div>
    </div>

    <?php

    if($fin and $decouchage) {
        echo "<p style='font-size: 300%'>Je suis actuellement en découchage 🚛</p>";
    }

    if(!$debut and !$fin or $journeeFinie) {
        ?>
        <a href="commencerJourneePost.php?commencer" class="button" style="margin-top: 10%">Commencer la journée</a>
        <?php
    }

    elseif ($estEnPause) {
        ?>
        <a href="commencerJourneePost.php?pauseFinir" class="button_light" style="margin-top: 8%">Quitter ma pause</a>
        <?php
    }

    elseif (!$fin) {
        ?>
        <a href="commencerJourneePost.php?finir&idHoraire=<?= $idHoraire ?>" class="button" style="margin-top: 10%">Finir la journée</a>
        <?php
        if($peutMettreEnPause) {
            ?>
            <a href="commencerJourneePost.php?pause" class="button_light" style="margin-top: 8%">Me mettre en pause</a>
            <?php
        }
    }

    else {
        if($decouchage) {
            ?>
            <a href="commencerJourneePost.php?découchageAnnuler&idHoraire=<?= $idHoraire ?>" class="button_light" style="margin-top: 6%">Je ne suis pas en découchage</a>
            <?php
        }
        else {
            ?>
            <a href="commencerJourneePost.php?découchage&idHoraire=<?= $idHoraire ?>" class="button_light" style="margin-top: 10%">Je suis en découchage</a>
            <?php
        }
    }

    ?>

    <?php
    // Gestion des erreurs de base de données
    if(isset($_GET['error'])) {
        $messageError = "";

        if($_GET['error'] == 'commencer')
            $messageError = "Nous n'avons pas réussi à initialiser votre journée.";

        elseif($_GET['error'] == 'pause')
            $messageError = "Nous n'avons pas réussi à vous mettre en pause.";

        elseif($_GET['error'] == 'pauseFinir')
            $messageError = "Nous n'avons pas réussi à vous enlever votre pause.";

        elseif($_GET['error'] == 'finir')
            $messageError = "Nous n'avons pas réussi à vous faire finir votre journée.";

        elseif($_GET['error'] == 'découchage')
            $messageError = "Nous n'avons pas réussi à vous mettre en découchage.";

        elseif($_GET['error'] == 'découchageAnnuler')
            $messageError = "Nous n'avons pas réussi à vous enlever le découchage.";

        $messageError .= "<br><br>L'administrateur a été averti de cet incident !";

        ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content">
                    <p>
                        <span style="font-weight: bold; color: red">Une erreur est survenue</span><br>
                        <br>
                        <?= $messageError ?>
                    </p>
                    <a href="#" onclick="closePopup()">J'ai compris</a>
                </div>
            </div>
        </div>
        <?php
    }


    if($peutMettreEnPause and !$journeeFinie or !$journeeFinie and $decouchage)
        echo "<a href='../ajouterOuModifier.php' class='button_light' style='margin-top: 10%'>Ajouter / Modifier une journée</a>";
    else
        echo "<a href='../ajouterOuModifier.php' class='button_light' style='margin-top: 30%'>Ajouter / Modifier une journée</a>";
    ?>

    <a href="../../planning.php" class="return_button">Retour</a>



    <script>
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
        }
    </script>
    </body>
    </html>
<?php } ?>