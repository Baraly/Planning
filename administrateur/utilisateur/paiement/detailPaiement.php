<?php

session_start();

if (!isset($_SESSION['adminAccess'])){
    header("location: ../../index.php");
}
else {

    ?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Détail d'un paiement</title>
        <style>
            @font-face {
                font-family: myFirstFont;
                src: url(../../../font-style/ABeeZee-Regular.ttf);
            }
            body {
                font-family: myFirstFont;
                margin: 10px 20px;
            }

            .interface {
                position: absolute;
                left: 20px;
                right: 20px;
                top: 120px;
                bottom: 5%;
                font-size: 22px;
            }

            .name {
                border: 1px solid white;
                box-shadow: 0 0 5px 5px rgba(0, 0, 0, 0.25);
                display: inline-block;
                padding: 8px 20px;
                border-radius: 10px;
            }

            .name > p {
                margin: 0;
                padding: 0;
            }

            .info {
                position: absolute;
                border: 1px solid black;
                right: 200px;
                left: 300px;
                top: 0;
                bottom: 150px;
                text-align: center;
                border-radius: 10px;
                overflow: auto;
                padding: 10px;
            }

            .info > p {
                text-align: left;
                margin: 10px 0;
                padding: 0;
            }

            .info > div {
                position: relative;
                height: 100%;
            }

            .info hr {
                margin: 10px;
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

    include_once '../../../function/bdd.php';
    include_once '../../../function/fonctionMois.php';

    $idPaiement = $_GET['idPaiement'];

    ?>

    <div style="margin-bottom: 20px">
        <a href="../../accueil.php" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <?php

    if (!empty($_GET['idPaiement'])) {
        $infoPaiement = $bdd->query("SELECT * FROM Paiement WHERE id = '$idPaiement'")->fetch();

        $userInfo = $bdd->query("SELECT * FROM User WHERE id = '" . $infoPaiement['idUser'] . "'")->fetch();

        ?>

        <p style="font-size: 24px; display: inline-block; margin: 0;">Utilisateur N° <?= $userInfo['id'] ?> - Détail du paiement N° <?= $infoPaiement['id'] ?></p>

        <div class="interface">
            <div class="name">
                <p style="margin-bottom: 8px">Nom : <?= strtoupper($userInfo['nom']) ?></p>
                <p>Prénom : <?= $userInfo['prenom'] ?></p>
            </div>

            <div class="info">
                <p>Date du paiement : <?= date('d/m/Y', strtotime($infoPaiement['datePaiement'])) ?></p>
                <p>Montant : <?= $infoPaiement['montant'] ?>€</p>
                <p style="margin-bottom: 0">Date de début : <?= date('d/m/Y', strtotime($infoPaiement['dateDebutAbonnement'])) ?></p>
                <p style="margin-top: 0">Date de fin : <?= date('d/m/Y', strtotime($infoPaiement['dateFinAbonnement'])) ?></p>
                <p>
                    État :
                    <?php

                    if($infoPaiement['etat'] == "EXECUTE AUTO")
                        echo "<span style='color: #2ECC71'>exécuté par l'utilisateur</span>";
                    if($infoPaiement['etat'] == "EXECUTE MANUELLE")
                        echo "<span style='color: #2ECC71'>exécuté par l'administrateur</span>";
                    elseif($infoPaiement['etat'] == "EN ATTENTE")
                        echo "<span style='color: #D35400'>en attente</span>";
                    elseif($infoPaiement['etat'] == "EXONERATION")
                        echo "<span style='color: #9B59B6'>exonéré</span>";

                    ?>
                </p>
            </div>
        </div>
        <?php

        if(isset($_GET['from'])) {
            if($_GET['from'] == "accueil")
                echo "<a class='button' style='position: absolute; left: 4%; bottom: 10%' href='../../accueil.php?page=2'>retour</a>";
        }
        else
            echo "<a class='button' style='position: absolute; left: 4%; bottom: 10%' href='historiquePaiement.php?idUser=" . $userInfo['id'] . "'>retour</a>";
        ?>
        </body>
        </html>
        <?php
    }
}
?>