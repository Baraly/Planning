<?php
session_start();

if (!isset($_SESSION['id']))
    header("location: ../../../index.php");

else {
    $bdd = null;
    require_once '../../../../function/bdd.php';
    require_once '../../../../function/fonctionMois.php';

    $id = $_SESSION['id'];
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF8"/>
        <title>Modifier une journée</title>
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

            form {
                font-size: 330%;
                margin-top: 10%;
            }

            form > div {
                margin-bottom: 30px;
            }

            input {
                font-size: 100%;
            }

            input[type='time'], input[type='date'] {
                border-radius: 20px;
            }

            input[type="submit"] {
                -webkit-appearance: none;
                margin-top: 16%;
                padding: 2% 8%;
                border: none;
                border-radius: 20px;
                color: white;
                background-color: #3B326C;
                box-shadow: #3B326C 0 60px 60px -40px;
                font-size: 110%;
            }

            select {
                font-size: 100%;
                border-radius: 20px;
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
                font-size: 310%;
            }

            .content > a {
                display: inline-block;
                background-color: #212F3D;
                color: white;
                text-decoration: none;
                padding: 2% 4%;
                border-radius: 20px;
            }
            input[type='checkbox'], input[type='radio'] {
                width: 10%;
                height: 75%;
            }
        </style>
    </head>
    <body>
    <h1 style="font-size: 350%">Modifier une journée</h1>

    <?php

    // Si on a un idHoraire
    if(isset($_GET['idHoraire'])){
        $idHoraire = $_GET['idHoraire'];

        // Si l'idHoraire correspond à l'id de l'utilisateur
        if($infoJournee = $bdd->query("SELECT * FROM Horaire WHERE idHoraire = '$idHoraire' AND idUser = '$id'")->fetch()) {
            ?>
            <form action="modifierJourneePost.php?mois=<?= $_GET['mois'] ?>&annee=<?= $_GET['annee'] ?>&idHoraire=<?= $idHoraire ?>" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr">
                    <div style="display: flex; align-items: center; justify-content: flex-end;">
                        <label for="date" style="text-align: right; margin-right: 20px">Date :</label>
                    </div>
                    <div style="text-align: left">
                        <input id="date" type="date" name="date" required value="<?= $infoJournee['datage'] ?>" disabled>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr">
                    <div style="display: flex; align-items: center; justify-content: flex-end;">
                        <label for="hd" style="text-align: right; margin-right: 20px">Heure de début :</label>
                    </div>
                    <div style="text-align: left">
                        <input id="hd" type="time" name="hDebut" required value="<?= $infoJournee['hDebut'] ?>">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr">
                    <div style="display: flex; align-items: center; justify-content: flex-end;">
                        <label for="hf" style="text-align: right; margin-right: 20px">Heure de fin :</label>
                    </div>
                    <div style="text-align: left">
                        <input id="hf" type="time" name="hFin" required value="<?= $infoJournee['hFin'] ?>">
                    </div>
                </div>
                <?php
                if($bdd->query("SELECT idSociete FROM User WHERE id = '$id' AND idSociete IS NOT NULL")->fetch()){
                    $coupureSociete = $bdd->query("SELECT temps FROM User, Coupure WHERE User.idSociete = Coupure.idSociete AND User.id = '$id'");
                    ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="display: flex; align-items: center; justify-content: flex-end;">
                            <label for="pause" style="text-align: right; margin-right: 20px">Pause :</label>
                        </div>
                        <div  style="text-align: left">
                            <select name="coupure" id='pause'>
                                <option value="automatique">Automatique</option>
                            <?php

                            $coupureTrouvee = false;

                            while($coupure = $coupureSociete->fetch()) {

                                if ((int)$coupure['temps'][0] * 10 + (int)$coupure['temps'][1] > 0)
                                    $coupureInterface = (int)$coupure['temps'][0] * 10 + (int)$coupure['temps'][1] . "h" . $coupure['temps'][3]  . $coupure['temps'][4];
                                else
                                    $coupureInterface = (int)$coupure['temps'][3] * 10 + (int)$coupure['temps'][4] . "min";

                                if ($infoJournee['coupure'] == $coupure['temps']) {
                                    $coupureTrouvee = true;
                                    echo "<option value='" . $coupure['temps'] . "' selected>" . $coupureInterface . "</option>";
                                }
                                else
                                    echo "<option value='" . $coupure['temps'] . "'>" . $coupureInterface . "</option>";
                            }

                            if(!$coupureTrouvee)
                                echo "<option value='" . $infoJournee['coupure'] . "' selected>" . $infoJournee['coupure'] . "</option>";

                            ?>
                            </select>
                        </div>
                    </div>
                    <?php
                }
                else {
                    ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="display: flex; align-items: center; justify-content: flex-end;">
                            <label for="pause" style="text-align: right; margin-right: 20px">Pause :</label>
                        </div>
                        <div  style="text-align: left">
                            <input id="pause" type="time" name="coupure" required value="<?= $infoJournee['coupure'] ?>">
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div style="display: grid; grid-template-columns: 1fr 1fr">
                    <div style="display: flex; align-items: center; justify-content: flex-end;">
                        <label style="text-align: right; margin-right: 20px">Découche :</label>
                    </div>
                    <div style="text-align: left">
                        <label for="oui">Oui </label><input id="oui" type="radio" name="decouche" value="1" <?php if($infoJournee['decouchage'] == 1) echo 'checked'; ?>>
                        <label for="non">Non </label><input id="non" type="radio" name="decouche" value="0" <?php if($infoJournee['decouchage'] == 0) echo 'checked'; ?>>
                    </div>
                </div>

                <input type="submit" value="Modifier la journée">

            </form>

            <?php

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
                                    <a href="#" onclick="closePopup()" style="color: white; background-color: #212F3D; border-radius: 20px; padding: 2% 6%; font-size: 100%">Annuler</a>
                                </div>
                                <div>
                                    <a href="../supprimer/supprimerJourneePost.php?mois=<?= $_GET['mois'] ?>&annee=<?= $_GET['annee'] ?>&idHoraire=<?= $_GET['idHoraire'] ?>&supprimer" style="color: white; background-color: red; border-radius: 20px; padding: 2% 6%; font-size: 100%">Confirmer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>

            <a href="modifierJournee.php?mois=<?= $_GET['mois'] ?>&annee=<?= $_GET['annee'] ?>&idHoraire=<?= $_GET['idHoraire']?>&supprimerJournee" style="color: white; background-color: red; border-radius: 20px; padding: 2% 4%; margin-top: 12%; display: inline-block; font-size: 310%">Supprimer la journée</a>


            <?php
            if(isset($_GET['error'])) {
                $messageError = "";

                if($_GET['error'] == 'BDD')
                    $messageError = "Nous n'avons pas réussi à modifier la journée.<br><br>L'administrateur a été averti de cet incident !";

                elseif($_GET['error'] == 'BDD_Admin')
                    $messageError = "Nous n'avons pas réussi à modifier la journée.<br><br>L'administrateur n'a pas pu être averti de cet incident !";

                elseif($_GET['error'] == 'BDD_suppression')
                    $messageError = "Nous n'avons pas réussi à supprimer la journée.<br><br>L'administrateur a été averti de cet incident !";

                elseif($_GET['error'] == 'BDD_suppression_admin')
                    $messageError = "Nous n'avons pas réussi à supprimer la journée.<br><br>L'administrateur n'a pas pu être averti de cet incident !";

                elseif($_GET['error'] == 'idHorairePasUser')
                    $messageError = "Vous essayez de supprimer une journée qui n'est pas la vôtre !";

                ?>
                <div class="overlay" id="overlay">
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
            elseif(isset($_GET['succes'])) {
                ?>
                <div class="overlay" id="overlay">
                    <div class="subOverlay">
                        <div class="content">
                            <p style="margin: 8% 0">
                                La journée a été modifiée avec succès !
                            </p>
                            <a href="#" onclick="closePopup()">Parfait !</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        // Si l'idHoraire ne correspond pas à l'id de l'utilisateur
        else {
            // Si l'utilisateur essaie d'accéder à un horaire qui n'est pas le sien
            if($bdd->query("SELECT * FROM Horaire WHERE idHoraire = '$idHoraire' AND idUser <> '$id'")->fetch()) {
                ?>
                <form action="modifierJournee.php" method="POST">
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="display: flex; align-items: center; justify-content: flex-end;">
                            <label for="date" style="text-align: right; margin-right: 20px">Date :</label>
                        </div>
                        <div style="text-align: left">
                            <input id="date" type="date" name="date" required value="<?= date('Y-m-d') ?>" disabled>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="display: flex; align-items: center; justify-content: flex-end;">
                            <label for="hd" style="text-align: right; margin-right: 20px">Heure de début :</label>
                        </div>
                        <div style="text-align: left">
                            <input id="hd" type="time" name="hDebut" required value="<?= date('H:i') ?>">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="display: flex; align-items: center; justify-content: flex-end;">
                            <label for="hf" style="text-align: right; margin-right: 20px">Heure de fin :</label>
                        </div>
                        <div style="text-align: left">
                            <input id="hf" type="time" name="hFin" required value="<?= date('H:i') ?>">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="display: flex; align-items: center; justify-content: flex-end;">
                            <label for="pause" style="text-align: right; margin-right: 20px">Pause :</label>
                        </div>
                        <div  style="text-align: left">
                            <input id="pause" type="time" name="coupure" required value="<?= date('H:i') ?>">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="display: flex; align-items: center; justify-content: flex-end;">
                            <label style="text-align: right; margin-right: 20px">Découche :</label>
                        </div>
                        <div style="text-align: left">
                            <label for="oui">Oui </label><input id="oui" type="radio" name="decouche" value="oui" >
                            <label for="non">Non </label><input id="non" type="radio" name="decouche" value="non" checked>
                        </div>
                    </div>

                    <input type="submit" value="Modifier la journée">

                </form>

                <div class="overlay" id="overlay">
                    <div class="subOverlay">
                        <div class="content">
                            <p>
                                <span style="font-weight: bold; color: red">Un problème est survenu</span><br>
                                <br>
                                Vous essayez d'accéder à une journée qui n'est pas la vôtre.<br> Veuillez retourner à la page précédente.
                            </p>
                            <a href="#" onclick="closePopup()">J'ai compris</a>
                        </div>
                    </div>
                </div>
                <?php
            }
            // Si l'idHoraire n'existe pas / plus
            else {
                ?>
                <form action="modifierJournee.php" method="POST">
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="display: flex; align-items: center; justify-content: flex-end;">
                            <label for="date" style="text-align: right; margin-right: 20px">Date :</label>
                        </div>
                        <div style="text-align: left">
                            <input id="date" type="date" name="date" required value="<?= date('Y-m-d') ?>" disabled>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="display: flex; align-items: center; justify-content: flex-end;">
                            <label for="hd" style="text-align: right; margin-right: 20px">Heure de début :</label>
                        </div>
                        <div style="text-align: left">
                            <input id="hd" type="time" name="hDebut" required value="<?= date('H:i') ?>">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="display: flex; align-items: center; justify-content: flex-end;">
                            <label for="hf" style="text-align: right; margin-right: 20px">Heure de fin :</label>
                        </div>
                        <div style="text-align: left">
                            <input id="hf" type="time" name="hFin" required value="<?= date('H:i') ?>">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="display: flex; align-items: center; justify-content: flex-end;">
                            <label for="pause" style="text-align: right; margin-right: 20px">Pause :</label>
                        </div>
                        <div  style="text-align: left">
                            <input id="pause" type="time" name="coupure" required value="<?= date('H:i') ?>">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="display: flex; align-items: center; justify-content: flex-end;">
                            <label style="text-align: right; margin-right: 20px">Découche :</label>
                        </div>
                        <div style="text-align: left">
                            <label for="oui">Oui </label><input id="oui" type="radio" name="decouche" value="oui" >
                            <label for="non">Non </label><input id="non" type="radio" name="decouche" value="non" checked>
                        </div>
                    </div>

                    <input type="submit" value="Modifier la journée">

                </form>

                <div class="overlay" id="overlay">
                    <div class="subOverlay">
                        <div class="content">
                            <p>
                                <span style="font-weight: bold; color: red">Un problème est survenu</span><br>
                                <br>
                                Il semblerait que la journée que vous demandez n'existe pas.<br> Veuillez retourner à la page précédente.
                            </p>
                            <a href="#" onclick="closePopup()">J'ai compris</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        echo "<a href='modifierJournee.php?mois=" . $_GET['mois'] . "&annee=" . $_GET['annee'] . "' class='return_button'>Retour</a>";
    }
    // Si on n'a pas d'idHoraire
    else {
        ?>

            <form action="modifierJournee.php" method="GET" id="myform">
                <div style="display: flex; justify-content: space-between; font-size: 100%">
                    <div style="display: grid; grid-template-columns: 1fr auto">
                        <div style="display: flex; align-items: center; justify-content: flex-end; margin-right: 10px">
                            <label for="mois">Mois :</label>
                        </div>
                        <div style="display: flex; justify-content: flex-start">
                            <select name="mois" id="mois" oninput="loadForm()">
                                <?php

                                for ($i = 1; $i <= 12; $i++) {
                                    if ((int)$_GET['mois'] == $i)
                                        echo "<option value='" . $i . "' selected>" . mois($i) . "</option>";
                                    else
                                        echo "<option value='" . $i . "'>" . mois($i) . "</option>";
                                }

                                ?>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr auto">
                        <div style="display: flex; align-items: center; justify-content: flex-end; margin-right: 10px">
                            <label for="annee">Année :</label>
                        </div>
                        <div style="display: flex; justify-content: flex-start">
                            <select name="annee" id="annee" oninput="loadForm()">
                                <?php

                                $infoAnnee = $bdd->query("SELECT YEAR(datage) AS annee FROM Horaire WHERE idUser = '$id' GROUP BY YEAR(datage)");

                                $rien = true;

                                while($donnees = $infoAnnee->fetch()) {
                                    $rien = false;
                                    if ((int)$_GET['annee'] == $donnees['annee'])
                                        echo "<option value='" . $donnees['annee'] . "' selected>" . $donnees['annee'] . "</option>";
                                    else
                                        echo "<option value='" . $donnees['annee'] . "'>" . $donnees['annee'] . "</option>";
                                }

                                if ($rien)
                                    echo "<option value='" . date('Y') . "' selected>" . date('Y') . "</option>";

                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </form>

        <?php

        $nbJourneeSupprimee = $bdd->query("SELECT COUNT(*) AS nb FROM Horaire, HorairePoubelle WHERE Horaire.idHoraire = HorairePoubelle.idHoraire AND Horaire.idUser = '$id'")->fetch();

        ?>

        <a href="../supprimer/supprimerJournee.php?mois=<?= $_GET['mois'] ?>&annee=<?= $_GET['annee'] ?>" style="color: royalblue; font-size: 300%; display: inline-block">Vous avez <?= $nbJourneeSupprimee['nb'] ?> <?php if((int)$nbJourneeSupprimee['nb'] <= 1) echo "journée"; else echo "journées"; ?> dans la poubelle</a>

        <?php

        $infoMois = $bdd->query("SELECT * FROM Horaire WHERE idUser = '$id' AND MONTH(datage) = '" . $_GET['mois'] . "' AND YEAR(datage) = '" . $_GET['annee'] . "' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle) ORDER BY datage");

        ?>
        <div style="position: absolute; border: 1px solid #212F3D; border-radius: 20px; padding: 1%; top: 26%; left: 4%; right: 4%; bottom: 18%; overflow: auto; font-size: 130%">
            <?php
            $rien = true;

            while($infoJournee = $infoMois->fetch()) {

                // Premier élément -> pas de margin-top
                if($rien) {
                    ?>
                    <a href="modifierJournee.php?mois=<?= $_GET['mois'] ?>&annee=<?= $_GET['annee'] ?>&idHoraire=<?= $infoJournee['idHoraire'] ?>" style="display: block">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; font-size: 275%; color: white; background-color: #212F3D; border-radius: 20px; padding: 2% 3%">
                            <p style="text-align: left; padding: 0; margin: 0"><?= date('d/m/Y', strtotime($infoJournee['datage'])) ?></p>
                            <p style="text-align: right; padding: 0; margin: 0"><?= date('H:i', strtotime($infoJournee['hDebut'])) ?> - <?php if($infoJournee['hFin']) echo date('H:i', strtotime($infoJournee['hFin'])); else echo "error"; ?></p>
                        </div>
                    </a>
                    <?php
                }
                else {
                    ?>
                    <a href="modifierJournee.php?mois=<?= $_GET['mois'] ?>&annee=<?= $_GET['annee'] ?>&idHoraire=<?= $infoJournee['idHoraire'] ?>" style="display: block; margin-top: 3%">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; font-size: 275%; color: white; background-color: #212F3D; border-radius: 20px; padding: 2% 3%">
                            <p style="text-align: left; padding: 0; margin: 0"><?= date('d/m/Y', strtotime($infoJournee['datage'])) ?></p>
                            <p style="text-align: right; padding: 0; margin: 0"><?= date('H:i', strtotime($infoJournee['hDebut'])) ?> - <?php if($infoJournee['hFin']) echo date('H:i', strtotime($infoJournee['hFin'])); else echo "error"; ?></p>
                        </div>
                    </a>
                    <?php
                }
                $rien = false;
            }

            if($rien) {
                echo "<p style='font-size: 225%; display: inline-block; margin: 0; padding: 0;'>Aucune journée pour cette période.</p>";
            }

            ?>
        </div>
        <?php

        echo "<a href='../ajouterOuModifier.php' class='return_button'>Retour</a>";

        if(isset($_GET['succesDelete'])) {
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            La journée a bien été mise à la poubelle.<br>
                            Elle sera définitivement supprimée dans 30 jours.
                        </p>
                        <a href="#" onclick="closePopup()">J'ai compris</a>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>

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

<?php }
?>