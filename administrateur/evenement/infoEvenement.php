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
        <title>Détail d'un évènement</title>
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
                left: 4%;
                right: 30%;
                top: 120px;
                bottom: 20%;
                font-size: 22px;
                box-shadow: 0 0 5px 5px lightgrey;
                padding: 20px;
                border-radius: 20px;
            }

            .interface > p {
                padding: 0;
                margin: 0 0 8px;
            }

            .interface a {
                text-decoration: none;
                color: white;
                background-color: royalblue;
                border-radius: 10px;
                padding: 4px 12px;
                box-shadow: 0 16px 16px -12px royalblue;
                /* box-shadow: #3B326C 0 60px 60px -40px; */
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
    include_once '../../function/fonctionMois.php';

    if(isset($_GET['traiterId']))
        $bdd->exec("UPDATE Evenement SET connaissance = NOW() WHERE id = '" . $_GET['id'] . "'");

    if (!empty($_GET['id'])) {
        $evenementInfo = $bdd->query("SELECT * FROM Evenement WHERE id = '" . $_GET['id'] . "'")->fetch();

        ?>
        <div style="margin-bottom: 20px">
            <a href="../accueil.php" style="color: black; text-decoration: none; display: inline-block">
                <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
                <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
            </a>
        </div>

        <p style="font-size: 24px; display: inline-block; margin: 0;">Évènement N° <?= $_GET['id'] ?></p>

        <div class="interface">
            <?php

            if($evenementInfo['idUser']) {
                $infoUser = $bdd->query("SELECT nom, prenom FROM User WHERE id = '" . $evenementInfo['idUser'] . "'")->fetch();
                ?>
                <p style="margin-bottom: 50px">Nom : <?= strtoupper($infoUser['nom']) ?> <?= $infoUser['prenom'] ?></p>
                <?php
            }
            else
                echo "<p style='margin-bottom: 50px'>Nom : Anonimous</p>";
            ?>
            <p style="margin-bottom: 30px">Date de l'évènement : <?= (int)date('d', strtotime($evenementInfo['dateEvenement'])) ?> <?= mois(date('m', strtotime($evenementInfo['dateEvenement']))) ?> <?= date('Y', strtotime($evenementInfo['dateEvenement'])) ?></p>
            <p>Type : <?= $evenementInfo['type'] ?></p>
            <p style="margin-bottom: 50px">Description : <?= $evenementInfo['description'] ?></p>
            <?php
            if($evenementInfo['important'])
                echo "<p><span style='color: white; background-color: red; padding: 3px 14px; border-radius: 10px; position: absolute; top: 20px; right: 20px'>important</span></p>";

            if($evenementInfo['connaissance'])
                echo "<p>Traité le " . (int)date('d', strtotime($evenementInfo['connaissance'])) . " " .  mois(date('m', strtotime($evenementInfo['connaissance']))) . " " . date('Y', strtotime($evenementInfo['connaissance'])) . "</p>";
            else
                echo "<p><a href='infoEvenement.php?id=" . $_GET['id'] . "&from=" . $_GET['from'] . "&traiterId'>Traiter cet évènement</a></p>";
            ?>
        </div>

        <?php
    }

    if($_GET['from'] == 'historique')
        echo "<a class='button' style='position: absolute; left: 4%; bottom: 10%' href='historique.php'>retour</a>";
    else
        echo "<a class='button' style='position: absolute; left: 4%; bottom: 10%' href='../accueil.php'>retour</a>";
    ?>
    </body>
    </html>
<?php } ?>