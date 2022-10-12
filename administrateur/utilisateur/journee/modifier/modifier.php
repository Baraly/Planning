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
        <title>Modifier une journée</title>
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

            <?php

        if(isset($_GET['error']) or isset($_GET['succes']) or isset($_GET['suppressionOK']) or isset($_GET['supprimerJournee'])) {
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
            <?php
        }

        ?>

            .listeJours{
                border: 1px solid black;
                border-radius: 10px;
                display: block;
                padding: 2%;
                overflow: auto;
                position: absolute;
                top: 35px;
                bottom: 0;
                right: 8%;
                left: 8%;
            }

            .listeJours a {
                display: grid;
                grid-template-columns: 1fr 2fr 2fr 1fr;
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
            input[type='checkbox'], input[type='radio'] {
                width: 10%;
                height: 75%;
            }
        </style>
    </head>
    <body>

    <?php

    $bdd = null;

    include_once '../../../../function/bdd.php';
    include_once '../../../../function/fonctionMois.php';

    ?>


    <div style="margin-bottom: 20px">
        <a href="../../../accueil.php" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <p style="font-size: 24px; display: inline-block; margin: 4px 0 0;">Modifier une journée</p>

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

        if(isset($_GET['idHoraire'])) {
            $infoJournee = $bdd->query("SELECT * FROM Horaire WHERE idHoraire = '" . $_GET['idHoraire'] . "'")->fetch();
            ?>
            <div>
                <form action="modifierPost.php?idUser=<?= $idUser ?>&idHoraire=<?= $_GET['idHoraire'] ?>" method="POST">
                    <div class="form" style="margin: 0; padding: 20px 20px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; margin-bottom: 6px">
                            <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                                <label for="date">Date :</label>
                            </div>
                            <div style="text-align: left">
                                <input type="date" name="date" id="date" required value="<?= $infoJournee['datage'] ?>" disabled>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; margin-bottom: 6px">
                            <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                                <label for="hDebut">Heure de début :</label>
                            </div>
                            <div style="text-align: left">
                                <input type="time" name="hDebut" id="hDebut" required value="<?= date('H:i', strtotime($infoJournee['hDebut'])) ?>">
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; margin-bottom: 6px">
                            <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                                <label for="hFin">Heure de fin :</label>
                            </div>
                            <div style="text-align: left">
                                <input type="time" name="hFin" id="hFin" required value="<?php if(!empty($infoJournee['hFin'])) echo date('H:i', strtotime($infoJournee['hFin'])); ?>">
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; margin-bottom: 6px">
                            <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                                <label for="coupure">Coupure :</label>
                            </div>
                            <div style="text-align: left">
                                <?php

                                if($userInfo['idSociete']){
                                    ?>
                                    <select name="coupure" id="coupure">
                                        <option value="automatique">Automatique</option>
                                        <?php

                                        $listeCoupure = $bdd->query("SELECT temps FROM Coupure WHERE idSociete = '" . $userInfo['idSociete'] . "'");

                                        $coupureTrouvee = false;

                                        while ($temps = $listeCoupure->fetch()) {
                                            if ($infoJournee['coupure'] == $temps['temps']) {
                                                $coupureTrouvee = true;
                                                echo "<option value='" . $temps['temps'] . "' selected>" . $temps['temps'] . "</option>";
                                            }
                                            else
                                                echo "<option value='" . $temps['temps'] . "'>" . $temps['temps'] . "</option>";
                                        }

                                        if(!$coupureTrouvee)
                                            echo "<option value='" . $infoJournee['coupure'] . "' selected>" . $infoJournee['coupure'] . "</option>";

                                        ?>
                                    </select>
                                    <?php
                                }
                                else {
                                    ?>
                                    <input type="time" name="coupure" id="coupure" required >
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr">
                            <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                                <label for="decouchage">Découchage :</label>
                            </div>
                            <div style="text-align: left">
                                <input type="checkbox" name="decouchage" id="decouchage" <?php if($infoJournee['decouchage'] == 1) echo 'checked'; ?>>
                            </div>
                        </div>
                        <div style="text-align: center; margin-top: 20px">
                            <input type="submit" value="Modifier">
                        </div>
                    </div>
                </form>
                <div style="text-align: center">
                    <a href="modifier.php?idUser=<?= $idUser ?>&idHoraire=<?= $_GET['idHoraire'] ?>&supprimerJournee" style="color: white; background-color: red; border-radius: 8px; padding: 4px 14px; margin-top: 20px; text-decoration: none; display: inline-block; font-size: 24px">Supprimer la journée</a>
                </div>
            </div>
            <?php
        }
        else {
            $mois = (int)date('m');
            $annee = (int)date('Y');

            if(isset($_POST['mois']))
                $mois = $_POST['mois'];

            if(isset($_POST['annee']))
                $annee = $_POST['annee'];

            ?>
            <div style="display: grid; grid-template-columns: 2fr 5fr; grid-gap: 20px; margin-top: 60px; margin-right: 6%; height: 65%">
                <div>
                    <div class="name" style="margin-top: 0">
                        <form action="modifier.php?idUser=<?= $_GET['idUser'] ?>" method="post" id="myform">
                            <div style="margin-bottom: 10px">
                                <label for="mois">Mois :</label>
                                <select name="mois" id="mois" oninput="loadForm()">
                                    <?php

                                    for($i = 1; $i <= 12; $i++) {
                                        if($mois == $i)
                                            echo "<option value='" . $i . "' selected>" . mois($i) . "</option>";
                                        else
                                            echo "<option value='" . $i . "'>" . mois($i) . "</option>";
                                    }

                                    ?>
                                </select>
                            </div>
                            <div>
                                <label for="annee">Année :</label>
                                <select name="annee" id="annee" oninput="loadForm()">
                                    <?php

                                    $listeAnnee = $bdd->query("SELECT YEAR(datage) as annee FROM Horaire WHERE idUser = '$idUser' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle) GROUP BY YEAR(datage)");

                                    $rien = true;
                                    while($anneeUser = $listeAnnee->fetch()) {
                                        $rien = false;

                                        if($annee == $anneeUser['annee'])
                                            echo "<option value='" . $anneeUser['annee'] . "' selected>" . $anneeUser['annee'] . "</option>";
                                        else
                                            echo "<option value='" . $anneeUser['annee'] . "'>" . $anneeUser['annee'] . "</option>";
                                    }

                                    if($rien)
                                        echo "<option value='" . $annee . "'>" . $annee . "</option>";

                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div style="display: block; position: relative; text-align: center">
                    <?php

                    $nbJourneePoubelle = $bdd->query("SELECT COUNT(*) AS nb FROM Horaire, HorairePoubelle WHERE Horaire.idHoraire = HorairePoubelle.idHoraire AND Horaire.idUser = '$idUser'")->fetch();

                    ?>
                    <div style="text-align: center">
                        <a style="color: royalblue; text-decoration: none; display: inline-block" href="../supprimer/supprimer.php?idUser=<?= $idUser ?>">Il y a <?php if($nbJourneePoubelle['nb'] > 1) echo $nbJourneePoubelle['nb'] . " journées"; else echo $nbJourneePoubelle['nb'] . " journée"; ?> en attente de suppression</a>
                    </div>
                    <div class="listeJours">
                        <ul style="margin: 0; padding: 0">
                        <?php

                        $rien = true;

                        $listeJournees = $bdd->query("SELECT * FROM Horaire WHERE idUser = '$idUser' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle) AND MONTH(datage) = '$mois' AND YEAR(datage) = '$annee' ORDER BY datage");

                        while ($jour = $listeJournees->fetch()) {
                            $rien = false;

                            if(empty($jour['hFin']))
                                echo "<li><a href='modifier.php?idUser=" . $idUser . "&idHoraire=" . $jour['idHoraire'] . "'><p>N° " . $jour['idHoraire'] . "</p><p>" . date('d/m/Y', strtotime($jour['datage'])) . "</p><p>" . date('H:i', strtotime($jour['hDebut'])) . " - /</p><p>/</p></a></li>";
                            else
                                echo "<li><a href='modifier.php?idUser=" . $idUser . "&idHoraire=" . $jour['idHoraire'] . "'><p>N° " . $jour['idHoraire'] . "</p><p>" . date('d/m/Y', strtotime($jour['datage'])) . "</p><p>" . date('H:i', strtotime($jour['hDebut'])) . " - " . date('H:i', strtotime($jour['hFin'])) . "</p><p>" . date('H', strtotime($jour['coupure'])) . "h" . date('i', strtotime($jour['coupure'])) . "</p></a></li>";
                        }

                        if($rien)
                            echo "<p style='text-align: center'>Il n'y a aucune donnée pour cette période</p>";

                        ?>
                        </ul>
                    </div>
                </div>
            </div>



            <?php
        }

        if (isset($_GET['error'])) {
            $message = "";
            if($_GET['error'] == 'modifier')
                $message = "La base de données n'a pas voulu modifier la journée.";
            elseif($_GET['error'] == 'suppression')
                $message = "La base de données n'a pas voulu 'supprimer' la journée.";
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

        if(isset($_GET['supprimerJournee'])) {
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            Vous êtes sur le point de supprimer une journée. <br>Voulez-vous continuer ?
                        </p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; justify-content: space-around">
                            <div>
                                <a href="#" onclick="closePopup()" style="color: white; background-color: #212F3D; border-radius: 10px; padding: 4px 14px; font-size: 24px">Annuler</a>
                            </div>
                            <div>
                                <a href="modifierPost.php?idUser=<?= $idUser ?>&idHoraire=<?= $_GET['idHoraire'] ?>&supprimer" style="color: white; background-color: red; border-radius: 10px; padding: 4px 14px; font-size: 24px">Confirmer</a>
                            </div>
                        </div>
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
                            La journée a été modifiée avec succès !
                        </p>
                        <a href="#" onclick="closePopup()">Parfait !</a>
                    </div>
                </div>
            </div>
            <?php
        }

        if(isset($_GET['suppressionOK'])) {
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            La journée a été supprimée avec succès !
                        </p>
                        <a href="#" onclick="closePopup()">Parfait !</a>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../../index.php?idUser=<?= $_GET['idUser'] ?>">retour</a>

    <script type="text/javascript">
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
        }

        function loadForm() {
            document.forms["myform"].submit();
        }
    </script>
    </body>
    </html>
<?php } ?>