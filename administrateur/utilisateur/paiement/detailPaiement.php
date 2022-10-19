<?php

session_start();

if (!isset($_GET['idUser'])) {
    header("location: ../../accueil.php");
}
else {

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
                    right: 340px;
                    left: 300px;
                    top: 0;
                    bottom: 150px;
                    text-align: center;
                    border-radius: 10px;
                    overflow: auto;
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

        $userInfo = $bdd->query("SELECT * FROM User WHERE id = " . $_GET['idUser'])->fetch();

        ?>

        <div style="margin-bottom: 20px">
            <a href="../../accueil.php" style="color: black; text-decoration: none; display: inline-block">
                <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
                <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
            </a>
        </div>

        <?php

        if (!empty($_GET['idUser'])) {

            ?>

            <p style="font-size: 24px; display: inline-block; margin: 0;">Utilisateur N° <?= $_GET['idUser'] ?> - Détail d'un paiement</p>

            <div class="interface">
                <div class="name">
                    <p style="margin-bottom: 8px">Nom : <?= strtoupper($userInfo['nom']) ?></p>
                    <p>Prénom : <?= $userInfo['prenom'] ?></p>
                </div>
            </div>
            <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="historiquePaiement.php?idUser=<?= $_GET['idUser'] ?>">retour</a>
            </body>
            </html>
            <?php
        }
    }
}
?>