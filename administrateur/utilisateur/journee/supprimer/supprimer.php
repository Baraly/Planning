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
        <title>Liste des suppressions</title>
        <style>
            @font-face {
                font-family: myFirstFont;
                src: url(../../../../font-style/ABeeZee-Regular.ttf);
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

            .name {
                border: 1px solid white;
                box-shadow: 0 0 5px 5px rgba(0, 0, 0, 0.25);
                display: inline-block;
                padding: 8px 20px;
                border-radius: 10px;
                margin-top: 6px;
            }

            .name > p {
                margin: 0;
                padding: 0;
            }

            select {
                border: none;
                border-radius: 6px;
            }

            input, select {
                margin: 0;
                padding: 1px 3px;
                font-size: 22px;
            }

            input[type="submit"] {
                margin: 20px 0;
                background-color: royalblue;
                color: white;
                border-radius: 0;
                box-shadow: none;
                border: none;
                padding: 4px 12px;
                cursor: pointer;
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
            .overlay .content {
                width: 60%;
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
                font-size: 26px;
            }

            .overlay .content a {
                display: inline-block;
                background-color: #212F3D;
                color: white;
                text-decoration: none;
                border-radius: 14px;
                padding: 5px 40px;
            }

            .listeJours{
                border: 1px solid black;
                border-radius: 10px;
                display: block;
                padding: 2%;
                overflow: auto;
                position: absolute;
                top: 20%;
                bottom: 16%;
                right: 12%;
                left: 26%;
            }

            .listeJours a {
                display: grid;
                grid-template-columns: 1fr 2fr 2fr 2fr;
                background-color: #212F3D;
                color: white;
                text-decoration: none;
                border-radius: 6px;
                padding: 3px 10px;
                font-size: 22px;
            }

            .listeJours a p{
                margin: 0;
                padding: 0;
                display: inline-block;
                text-align: center;
            }

            li {
                list-style-type: none;
                padding: 0;
                margin: 0 0 6px;
            }
        </style>
    </head>
    <body>

    <?php

    $bdd = null;

    include_once '../../../../function/bdd.php';
    include_once '../../../../function/fonctionJours.php';

    ?>


    <div style="margin-bottom: 20px">
        <a href="../../../accueil.php" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <p style="font-size: 24px; display: inline-block; margin: 4px 0 0;">Liste des suppressions</p>

    <?php

    $idUser = $_GET['idUser'];
    $userInfo = $bdd->query("SELECT * FROM User WHERE id = '$idUser'")->fetch();

    ?>

    <div class="interface">
        <div class="name">
            <p style="margin-bottom: 8px">Nom : <?= strtoupper($userInfo['nom']) ?></p>
            <p>Prénom : <?= $userInfo['prenom'] ?></p>
        </div>

            <?php

            $listeJourneesPoubelle = $bdd->query("SELECT * FROM Horaire, HorairePoubelle WHERE Horaire.idHoraire = HorairePoubelle.idHoraire AND Horaire.idUser = '$idUser'");

            ?>
            <div class="listeJours">
                <ul style="margin: 0; padding: 0">
                    <?php

                    $rien = true;

                    echo "<li><a href='#' style='background-color: white; color: #212F3D'><p></p><p>Date</p><p>Horaire</p><p>Suppression dans</p></a></li>";

                    while ($jour = $listeJourneesPoubelle->fetch()) {
                        $rien = false;

                        $jourRestant = differenceJours(date('Y-m-d'), $jour['dateSuppression']);

                        if($jourRestant > 1) {
                            ?>
                            <li><a href='#' onclick="openPopupHoraire('overlay_idHoraire<?= $jour['idHoraire'] ?>')"><p>N° <?= $jour['idHoraire'] ?></p><p><?= date('d/m/Y', strtotime($jour['datage'])) ?></p><p><?= date('H:i', strtotime($jour['hDebut'])) ?> - <?= date('H:i', strtotime($jour['hFin'])) ?></p><p><?= $jourRestant ?> jours</p></a></li>
                            <?php
                        }
                        else{
                            ?>
                            <li><a href='#' onclick="openPopupHoraire('overlay_idHoraire<?= $jour['idHoraire'] ?>')"><p>N° <?= $jour['idHoraire'] ?></p><p><?= date('d/m/Y', strtotime($jour['datage'])) ?></p><p><?= date('H:i', strtotime($jour['hDebut'])) ?> - <?= date('H:i', strtotime($jour['hFin'])) ?></p><p><?= $jourRestant ?> jour</p></a></li>
                            <?php
                        }

                        ?>
                        <div class="overlay" id="overlay_idHoraire<?= $jour['idHoraire'] ?>" style="display: none">
                            <div class="subOverlay">
                                <div class="content">
                                    <p>
                                        Voulez-vous restaurer la journée du <?= date('d/m/Y', strtotime($jour['datage'])) ?> ?
                                    </p>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; justify-content: space-around">
                                        <div>
                                            <a href="#" onclick="closePopupHoraire('overlay_idHoraire<?= $jour['idHoraire'] ?>')" style="color: white; background-color: #212F3D; border-radius: 10px; padding: 4px 14px; font-size: 24px">Annuler</a>
                                        </div>
                                        <div>
                                            <a href="supprimerPost.php?idHoraire=<?= $jour['idHoraire'] ?>&idUser=<?= $idUser ?>&restaurer" style="color: white; background-color: #212F3D; border-radius: 10px; padding: 4px 14px; font-size: 24px">Confirmer</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }

                    if($rien)
                        echo "<p style='text-align: center'>Il n'y a aucune donnée dans cette poubelle.</p>";

                    ?>
                </ul>
            </div>

        <?php

        if (isset($_GET['error'])) {
            $message = "";
            if($_GET['error'] == 'BDD')
                $message = "La base de données n'a pas voulu restaurer la journée.";
            elseif($_GET['error'] == 'dateDoublon')
                $message = "Cet utilisateur possède déjà une journée avec la même date ! Il est donc impossible de restaurer la journée du " . date('d/m/Y', strtotime($_GET['date']));
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            <span style="font-weight: bold; color: red">Un problème est survenu</span><br>
                            <br>
                            <?= $message ?>
                        </p>
                        <a href="#" onclick="closePopup()">J'ai compris</a>
                    </div>
                </div>
            </div>
            <?php
        }

        if (isset($_GET['succes'])) {
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            La journée a bien été rétablie avec succès !
                        </p>
                        <a href="#" onclick="closePopup()">Parfait !</a>
                    </div>
                </div>
            </div>
            <?php
        }

        ?>
    </div>
    <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../modifier/modifier.php?idUser=<?= $_GET['idUser'] ?>">retour</a>

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
<?php } ?>