<?php

session_start();

if (!isset($_SESSION['adminAccess'])){
    header("location: ../index.php");
}
else {

    ?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Title</title>
        <style>
            @font-face {
                font-family: myFirstFont;
                src: url(../../font-style/ABeeZee-Regular.ttf);
            }
            body {
                font-family: myFirstFont;
                margin: 10px 20px;
            }

            .interface {
                position: absolute;
                top : 110px;
                bottom: 5%;
                left: 20px;
                right: 20px;
                font-size: 22px;
            }

            .button {
                text-align: center;
                display: inline-block;
                background-color: #212F3D;
                border-radius: 10px;
                font-size: 22px;
                color: white;
                text-decoration: none;
                padding: 4px 20px;
            }
        </style>
    </head>
    <body>

    <?php

    $bdd = null;

    include_once '../../function/bdd.php';

    ?>


    <div style="margin-bottom: 20px">
        <a href="../accueil.php" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <?php

    if (!empty($_GET['nomSociete'])) {

    $info = $bdd->query("SELECT * FROM Societe WHERE nomSociete = '" . $_GET['nomSociete'] . "'")->fetch();

    ?>

    <p style="font-size: 24px; display: inline-block; margin: 0;">Information sur la société : <?= $_GET['nomSociete'] ?></p>

    <div class="interface">
        <?php

        $nombre = $bdd->query("SELECT COUNT(*) AS nb FROM User WHERE idSociete = '"  . $info['id'] . "'")->fetch();

        ?>
            <p>Nombre d'utilisateurs : <?= $nombre['nb'] ?></p>
            <div style="border: 1px solid lightgrey; border-radius: 10px; box-shadow: 0 0 5px 5px lightgrey; padding: 1% 3%; margin: 0 20px; overflow: auto; display: inline-block; height: 40%">
                <?php

                $infoCoupure = $bdd->query("SELECT * FROM Coupure WHERE idSociete = '" . $info['id'] . "'");

                while ($donnes = $infoCoupure->fetch()) {
                    echo "<p style='margin-bottom: 10px; padding: 0; display: inline-block'>" . $donnes['borneDebut'] . " -> " . $donnes['borneFin'] . " = " . $donnes['temps'] . "</p><br>";
                }

                ?>
            </div>
        <?php
        }
        ?>
    </div>
    <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../accueil.php">retour</a>
    </body>
    </html>
<?php } ?>