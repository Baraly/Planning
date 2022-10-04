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
        <title>Ajouter une journée</title>
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

            .form {
                font-size: 24px;
            }

            .form > div {
                margin: 10px 0;
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

            input[type='checkbox'], input[type='radio'] {
                width: 10%;
                height: 75%;
            }

            <?php

        if(isset($_GET['error']) or isset($_GET['succes'])) {
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
        </style>
    </head>
    <body>

    <?php

    $bdd = null;

    include_once '../../../../function/bdd.php';

    ?>


    <div style="margin-bottom: 20px">
        <a href="../../../accueil.php" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <p style="font-size: 24px; display: inline-block; margin: 4px 0 0;">Ajouter une journée</p>

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

        if (isset($_GET['error'])) {
            $message = "";

            if($_GET['error'] == 'date')
                $message = "La journée du " . date('d/m/Y', strtotime($_GET['date'])) . " existe déjà, l'utilisateur ne peut pas avoir deux journées avec une même date !";
            elseif ($_GET['error'] == 'BDD')
                $message = "La base de données n'a pas voulu ajouter la journée.";
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

            <form action="ajouterPost.php?idUser=<?= $idUser ?>" method="POST">
                <div class="form" style="margin: 0; padding: 20px 20px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                            <label for="date">Date :</label>
                        </div>
                        <div style="text-align: left">
                            <input type="date" name="date" id="date" value="<?= $_GET['date'] ?>" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                            <label for="hDebut">Heure de début :</label>
                        </div>
                        <div style="text-align: left">
                            <input type="time" name="hDebut" id="hDebut" value="<?= $_GET['hDebut'] ?>" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                            <label for="hFin">Heure de fin :</label>
                        </div>
                        <div style="text-align: left">
                            <input type="time" name="hFin" id="hFin" value="<?= $_GET['hFin'] ?>" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                            <label for="coupure">Coupure :</label>
                        </div>
                        <div style="text-align: left">
                            <?php

                            if($userInfo['idSociete']){
                                ?>
                                <select name="coupure" id="coupure">
                                    <option value="automatique" <?php if($_GET['coupure'] == 'automatique') echo "selected"; ?>>Automatique</option>
                                    <?php

                                    $listeCoupure = $bdd->query("SELECT temps FROM Coupure WHERE idSociete = '" . $userInfo['idSociete'] . "'");

                                    while ($temps = $listeCoupure->fetch()) {
                                        if ($_GET['coupure'] == $temps['temps'])
                                            echo "<option value='" . $temps['temps'] . "' selected>" . $temps['temps'] . "</option>";
                                        else
                                            echo "<option value='" . $temps['temps'] . "'>" . $temps['temps'] . "</option>";
                                    }

                                    ?>
                                </select>
                                <?php
                            }
                            else {
                                ?>
                                <input type="time" name="coupure" id="coupure" value="<?= $_GET['coupure'] ?>" required>
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
                            <input type="checkbox" name="decouchage" id="decouchage" <?php if($_GET['decouchage'] == 1) echo "checked"; ?>>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 20px">
                        <input type="submit" value="Ajouter">
                    </div>
                </div>
            </form>
            <?php
        }
        else {

            ?>
            <form action="ajouterPost.php?idUser=<?= $idUser ?>" method="POST">
                <div class="form" style="margin: 0; padding: 20px 20px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                            <label for="date">Date :</label>
                        </div>
                        <div style="text-align: left">
                            <input type="date" name="date" id="date" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                            <label for="hDebut">Heure de début :</label>
                        </div>
                        <div style="text-align: left">
                            <input type="time" name="hDebut" id="hDebut" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
                        <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                            <label for="hFin">Heure de fin :</label>
                        </div>
                        <div style="text-align: left">
                            <input type="time" name="hFin" id="hFin" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr">
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

                                while ($temps = $listeCoupure->fetch()) {
                                    echo "<option value='" . $temps['temps'] . "'>" . $temps['temps'] . "</option>";
                                }

                                ?>
                            </select>
                            <?php
                        }
                        else {
                            ?>
                            <input type="time" name="coupure" id="coupure" required>
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
                            <input type="checkbox" name="decouchage" id="decouchage">
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 20px">
                        <input type="submit" value="Ajouter">
                    </div>
                </div>
            </form>
        <?php
        }

        if (isset($_GET['succes'])) {
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            La journée a été ajoutée avec succès !
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
    </script>
    </body>
    </html>
<?php } ?>