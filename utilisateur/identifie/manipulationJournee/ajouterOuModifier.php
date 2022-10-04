<?php
session_start();

if (!isset($_SESSION['id']))
    header("location: ../../index.php");

else {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF8"/>
        <title>Ajout / Modification - Planning</title>
        <style>
            @font-face {
                font-family: myFirstFont;
                src: url(../../../font-style/ABeeZee-Regular.ttf);
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
        </style>
    </head>
    <body>
    <h1 style="font-size: 350%">Souhaitez-vous ?</h1>

        <a href="ajouter/ajouterJournee.php" class="button_light" style="margin-top: 30%; display: inline-block">Ajouter une journée</a>
        <a href="modifier/modifierJournee.php?mois=<?= date('m') ?>&annee=<?= date('Y') ?>" class="button_light" style="margin-top: 10%; display: inline-block">Modifier une journée</a>

    <a href="commencer/commencerJournee.php" class="return_button">Retour</a>
    </body>
    </html>

<?php }
?>