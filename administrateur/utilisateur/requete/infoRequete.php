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
        <title>Title</title>
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
                display: grid;
                grid-template-columns: 1fr 2fr;
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
                display: grid;
                grid-template-rows: auto 50px;
                height: 80%;
                border: 1px solid black;
                text-align: center;
                border-radius: 10px;
            }

            .info p {
                margin: 10px 20px;
                padding: 0;
                text-align: left;
            }

            ul {
                margin: 10px;
                padding: 10px;
                overflow: auto;
                border-radius: 10px;
                font-size: 18px;
            }

            li {
                padding: 0;
                position: relative;
                list-style-type: none;
                width: 100%;
                margin: 0 0 10px;
            }

            .divUser {
                position: absolute;
                background-color: #E5E8E8;
                border-radius: 10px;
                padding: 4px 8px;
                left: 10px;
                right: 50%;
                z-index: 2;
            }

            .divAdmin {
                position: absolute;
                background-color: royalblue;
                color: white;
                border-radius: 10px;
                padding: 4px 8px;
                right: 10px;
                left: 50%;
                z-index: 2;
            }

            li > p {
                margin: 0;
                padding: 0;
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

            textarea {
                font-family: myFirstFont;
                font-size: 20px;
                padding: 10px;
                width: 60%;
                height: 20px;
            }

            input[type="submit"] {
                margin: 0;
                background-color: royalblue;
                color: white;
                border-radius: 8px;
                box-shadow: none;
                border: none;
                padding: 4px 8px;
                cursor: pointer;
                font-size: 22px;
                display: inline-block;
                left: 50%;
                top: 50%;
                -ms-transform: translateY(-50%);
                transform: translateY(-50%);
            }
        </style>
    </head>
    <body>

    <?php

    $bdd = null;

    include_once '../../../function/bdd.php';

    ?>


    <div style="margin-bottom: 20px">
        <a href="../../accueil.php" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <?php

    if (!empty($_GET['idUser'])) {

    $userInfo = $bdd->query("SELECT * FROM User WHERE id = '" . $_GET['idUser'] . "'")->fetch();

    ?>

    <p style="font-size: 24px; display: inline-block; margin: 0;">Utilisateur N° <?= $_GET['idUser'] ?> - Requête N° <?= $_GET['idRequete'] ?></p>

    <div class="interface">

        <div>

            <div style="margin: 0; padding: 0">
                <div class="name">
                    <p style="margin-bottom: 8px">Nom : <?= strtoupper($userInfo['nom']) ?></p>
                    <p>Prénom : <?= $userInfo['prenom'] ?></p>
                </div>
            </div>

            <div style="margin: 20px 0; padding: 0">
                <div class="name">
                    <?php

                    $infoRequete = $bdd->query("SELECT * FROM Requete WHERE id = '" . $_GET['idRequete'] . "'")->fetch();

                    ?>
                    <p>Type : <?= $infoRequete['type'] ?></p>
                    <p>Date d'ouverture : <?= date('d', strtotime($infoRequete['dateOuverture'])) ?>/<?= date('m', strtotime($infoRequete['dateOuverture'])) ?>/<?= date('Y', strtotime($infoRequete['dateOuverture'])) ?></p>
                    <p>
                        État :
                        <?php

                        if($infoRequete['dateCloture']) {
                            echo "<span style='color: #8CCD75'>cloturé</span> (le " . date('d', strtotime($infoRequete['dateCloture'])) ?>/<?= date('m', strtotime($infoRequete['dateCloture'])) ?>/<?= date('Y', strtotime($infoRequete['dateCloture'])) . ")";
                        }
                        else
                            echo "<span style='color: #EF5050'>à traiter</span>";

                        ?>
                    </p>
                    <?php

                    if (!$infoRequete['dateCloture'])
                        echo "<p style='margin-top: 10px; text-align: center'><a style='display: inline-block; text-decoration: none; color: royalblue' href='requetePost.php?idRequete=". $_GET['idRequete'] ."&cloturer'>Clôturer la requête</a></p>";
                    ?>
                </div>
            </div>

        </div>

        <div style="position: relative">
            <div class="info" style="position: absolute; top: 0; bottom: 10%; right: 0; left: 0">
                <ul>
                    <?php

                    $listeMessages = $bdd->query("SELECT * FROM MessageRequete WHERE idRequete = '" . $_GET['idRequete'] . "' ORDER BY dateEnvoie");

                    $rien = true;

                    while ($message = $listeMessages->fetch()) {
                        $rien = false;
                        ?>
                        <li>
                            <?php

                            // réponse administrateur
                            if (!$message['idUser']) {
                                ?>
                                <div class="divAdmin">
                                    <p style="margin: 0; padding: 0; color: #85C1E9">
                                        <?= date('d/m/Y', strtotime($message['dateEnvoie'])) ?>
                                        à
                                        <?= date('H:i', strtotime($message['dateEnvoie'])) ?>
                                    </p>
                                    <p style="margin: 0; padding: 0">
                                        <?= $message['message'] ?>
                                    </p>
                                </div>
                                <div class="divAdmin" style="position: relative; color: white; background-color: white; z-index: 0; width: 40%">
                                    <p style="margin: 0; padding: 0; color: white">
                                        <?= date('d/m/Y', strtotime($message['dateEnvoie'])) ?>
                                        à
                                        <?= date('H:i', strtotime($message['dateEnvoie'])) ?>
                                    </p>
                                    <p style="margin: 0; padding: 0">
                                        <?= $message['message'] ?>
                                    </p>
                                </div>
                                <?php
                                //echo "<p style='padding: 0; color: white; width: 40%; margin: 10px 0'> " . $message['message'] . "</p>";
                            }
                            // réponse utilisateur
                            else {
                                ?>
                                <div class="divUser">
                                    <p style="margin: 0; padding: 0; color: #7F8C8D">
                                        <?= date('d/m/Y', strtotime($message['dateEnvoie'])) ?>
                                        à
                                        <?= date('H:i', strtotime($message['dateEnvoie'])) ?>
                                    </p>
                                    <p style="margin: 0; padding: 0">
                                        <?= $message['message'] ?>
                                    </p>
                                </div>
                                <div class="divUser" style="position: relative; color: white; background-color: white; z-index: 0; width: 40%">
                                    <p style="margin: 0; padding: 0; color: white">
                                        <?= date('d/m/Y', strtotime($message['dateEnvoie'])) ?>
                                        à
                                        <?= date('H:i', strtotime($message['dateEnvoie'])) ?>
                                    </p>
                                    <p style="margin: 0; padding: 0">
                                        <?= $message['message'] ?>
                                    </p>
                                </div>
                                <?php
                                //echo "<p style='padding: 0; color: white; width: 40%; margin: 10px 0'> " . $message['message'] . "</p>";
                            }
                            ?>
                        </li>
                        <?php
                    }

                    if($rien) {
                        echo "<li style='text-align: center'><p>La conversation a été supprimée</p></li>";
                    }

                    if ($infoRequete['dateCloture'] and !$rien)
                        echo "<li><p style='text-align: center; margin-top: 20px'>La requête a été clôturée</p></li>";
                    ?>
                </ul>
                <?php

                if (!$infoRequete['dateCloture']){
                    ?>
                    <form action="requetePost.php?idRequete=<?= $_GET['idRequete'] ?>" method="POST">
                        <textarea name="message"></textarea>
                        <input type="submit" value="répondre">
                    </form>
                    <?php
                }


                ?>
            </div>
        </div>

    </div>
    <?php } ?>
    <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../index.php?idUser=<?= $_GET['idUser'] ?>">retour</a>
    </body>
    </html>
<?php } ?>