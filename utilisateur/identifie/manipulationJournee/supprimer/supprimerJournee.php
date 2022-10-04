<?php

session_start();

if (!isset($_SESSION['id']))
    header("location: ../../../index.php");

else {
    $bdd = null;
    require_once '../../../../function/bdd.php';
    require_once '../../../../function/fonctionJours.php';

    $id = $_SESSION['id'];

    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF8"/>
        <title>Historique suppression</title>
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

            .overlay {
                position: fixed;
                top: 0;
                bottom: 0;
                right: 0;
                left: 0;
                background-color: rgba(39, 55, 70, 0.9);
                z-index: 20;
                display: none;
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
                font-size: 275%;
            }

            .content > a {
                display: inline-block;
                background-color: #212F3D;
                color: white;
                text-decoration: none;
                padding: 2% 4%;
                border-radius: 20px;
            }
        </style>
    </head>
    <body>
    <h1 style="font-size: 350%">Historique des suppressions de journées</h1>
        <div style="border: 1px solid #212F3D; border-radius: 20px; padding: 2% 2%; margin: 10% 4% 0;max-height: 70%; overflow: auto; display: block; font-size: 135%">
            <?php
            $infoPoubelle = $bdd->query("SELECT Horaire.idHoraire AS id, datage, dateSuppression FROM Horaire, HorairePoubelle WHERE Horaire.idHoraire = HorairePoubelle.idHoraire AND Horaire.idUser = '$id'");
            $rien = true;

            while($infoJournee = $infoPoubelle->fetch()) {
                $nbJours = differenceJours($infoJournee['dateSuppression'], date('Y-m-d'));

                // Premier élément -> pas de margin-top
                if($rien) {
                    ?>
                    <a href="#">
                        <div style="display: grid; grid-template-columns: 1fr 2fr; font-size: 225%; color: #212F3D; padding: 2% 3%">
                            <p style="padding: 0; margin: 0">date</p>
                            <p style="text-align: right; padding: 0; margin: 0">supprimer dans</p>
                        </div>
                    </a>
                    <a href="#" onclick="openPopupHoraire('overlay_idHoraire<?= $infoJournee['id'] ?>')" style="display: block">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; font-size: 225%; color: white; background-color: #212F3D; border-radius: 20px; padding: 2% 3%">
                            <p style="text-align: left; padding: 0; margin: 0"><?= date('d/m/Y', strtotime($infoJournee['datage'])) ?></p>
                            <p style="text-align: right; padding: 0; margin: 0">
                                <?php if($nbJours <= 1) echo $nbJours . " jour"; else echo $nbJours . " jours"; ?>
                            </p>
                        </div>
                    </a>
                    <?php
                }
                else {
                    ?>
                    <a href="#" onclick="openPopupHoraire('overlay_idHoraire<?= $infoJournee['id'] ?>')" style="display: block; margin-top: 3%">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; font-size: 225%; color: white; background-color: #212F3D; border-radius: 20px; padding: 2% 3%">
                            <p style="text-align: left; padding: 0; margin: 0"><?= date('d/m/Y', strtotime($infoJournee['datage'])) ?></p>
                            <p style="text-align: right; padding: 0; margin: 0">
                                <?php if($nbJours <= 1) echo $nbJours . " jour"; else echo $nbJours . " jours"; ?>
                            </p>
                        </div>
                    </a>
                    <?php
                }
                ?>
                <div class="overlay" id="overlay_idHoraire<?= $infoJournee['id'] ?>">
                    <div class="subOverlay">
                        <div class="content">
                            <p>
                                Voulez-vous restaurer la journée du <?= date('d/m/Y', strtotime($infoJournee['datage'])) ?> ?
                            </p>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; justify-content: space-around">
                                <div>
                                    <a href="#" onclick="closePopupHoraire('overlay_idHoraire<?= $infoJournee['id'] ?>')" style="color: white; background-color: #212F3D; border-radius: 20px; padding: 2% 5%; font-size: 100%">Annuler</a>
                                </div>
                                <div>
                                    <a href="../supprimer/supprimerJourneePost.php?mois=<?= $_GET['mois'] ?>&annee=<?= $_GET['annee'] ?>&idHoraire=<?= $infoJournee['id'] ?>&restaurer" style="color: white; background-color: #212F3D; border-radius: 20px; padding: 2% 4%; font-size: 100%">Confirmer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $rien = false;
            }

            if($rien) {
                echo "<p style='font-size: 225%; display: inline-block'>Votre poubelle est vide.</p>";
            }

            ?>
        </div>
        <?php

        echo "<a href='../modifier/modifierJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "' class='return_button'>Retour</a>";

        if(isset($_GET['error'])) {
            $messageError = "";

            if($_GET['error'] == 'BDD_suppression')
                $messageError = "Nous n'avons pas réussi à restaurer la journée.<br><br>L'administrateur a été averti de cet incident !";
            elseif($_GET['error'] == 'BDD_suppression_admin')
                $messageError = "Nous n'avons pas réussi à restaurer la journée.<br><br>L'administrateur n'a pas pu être averti de cet incident !";
            elseif($_GET['error'] == 'dejaJournee') {
                $date = $_GET['date'];
                $messageError = "Vous possédez déjà une journée à la date du " . date('d/m/Y', strtotime($date)) . ".<br>Vous ne pouvez pas restaurer plusieurs journées avec une même date !";
            }

            ?>
            <div class="overlay" id="overlay" style="display: block; font-size: 130%">
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
        elseif(isset($_GET['succesDelete'])){
            ?>
            <div class="overlay" id="overlay" style="display: block; font-size: 130%">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            La journée a bien été restaurée !
                        </p>
                        <a href="#" onclick="closePopup()">Parfait !</a>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>

    <script type="text/javascript">
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
        }

        function closePopupHoraire(id) {
            document.getElementById(id).style.display = "none";
        }

        function openPopupHoraire(id) {
            document.getElementById(id).style.display = "block";
        }
    </script>
    </body>
    </html>
    <?php
}