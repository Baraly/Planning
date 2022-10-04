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
                left: 20px;
                right: 20px;
                top: 120px;
                bottom: 5%;
                font-size: 22px;
                padding: 20px;
            }

            .interface > p {
                margin: 10px 0;
                padding: 0;
            }

            input[type="submit"] {
                margin: 10px 0;
                background-color: royalblue;
                color: white;
                border-radius: 8px;
                box-shadow: none;
                border: none;
                padding: 4px 20px;
                cursor: pointer;
                font-size: 22px;
            }

            ul {
                margin: 0 20px;
                padding: 0 0;
                position: absolute;
                top: 4px;
                bottom: 0;
                right: 0;
                left: 0;
            }

            li, .input {
                margin: 18px 0;
                list-style-type: none;
            }

            input {
                font-size: 20px;
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

    include_once '../../function/bdd.php';

    ?>


    <div style="margin-bottom: 20px">
        <a href="../accueil.php" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <p style="font-size: 24px; display: inline-block; margin: 0;">Création d'une entreprise</p>

    <?php

    if (isset($_GET['error'])) {
        $message = "";
        if ($_GET['error'] == "heure")
            $message =  "Vous vous êtes trompé dans vos heures, veuillez recommencer";
        elseif ($_GET['error'] == "bdd")
            $message =  "La base de donnée a refusé de renseigner l'entreprise";
        elseif ($_GET['error'] == "nom")
            $message =  "Une entreprise a déjà le même nom";
        ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content">
                    <p>
                        <span style="font-weight: bold; color: red">Un problème est survenu</span><br>
                        <?= $message ?>
                    </p>
                    <a href="#" onclick="closePopup()">Mince OK</a>
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
                        L'entreprise a bien été enregistrée !
                    </p>
                    <a href="#" onclick="closePopup()">OK parfait !</a>
                </div>
            </div>
        </div>
        <?php
    }

    if (isset($_GET['nombre']) and isset($_GET['nom'])) {
        ?>
        <div class="interface">
            <p>Nom : <?= strtoupper($_GET['nom']) ?></p>
            <p>Nombre de coupure : <?= $_GET['nombre'] ?></p>
            <div>
                <form action="ajoutSocietePost.php?nom=<?= $_GET['nom'] ?>&nombre=<?= $_GET['nombre'] ?>" method="POST" style="position: relative">
                    <ul>
                        <?php

                        for ($i = 0; $i < (int)$_GET['nombre']; $i++) {
                            if ($i == 0) {
                                echo "<li>N° " . ($i+1) . " -> <label for='debut" . ($i+1) . "'>Début : </label><input id='debut" . ($i+1) . "' type='time' name='debut" . ($i+1) . "' value='00:00' required> <label for='fin" . ($i+1) . "'>Fin : </label><input id='fin" . ($i+1) . "' type='time' name='fin" . ($i+1) . "' required> <label for='coupure" . ($i+1) . "'>Coupure : </label><input id='coupure" . ($i+1) . "' type='time' name='coupure" . ($i+1) . "' required></li>";
                            }
                            elseif ($i == (int)$_GET['nombre']-1) {
                                echo "<li>N° " . ($i+1) . " -> <label for='debut" . ($i+1) . "'>Début : </label><input id='debut" . ($i+1) . "' type='time' name='debut" . ($i+1) . "' required> <label for='fin" . ($i+1) . "'>Fin : </label><input id='fin" . ($i+1) . "' type='time' name='fin" . ($i+1) . "' value='23:59' required> <label for='coupure" . ($i+1) . "'>Coupure : </label><input id='coupure" . ($i+1) . "' type='time' name='coupure" . ($i+1) . "' required></li>";
                            }
                            else {
                                echo "<li>N° " . ($i + 1) . " -> <label for='debut" . ($i + 1) . "'>Début : </label><input id='debut" . ($i + 1) . "' type='time' name='debut" . ($i + 1) . "' required> <label for='fin" . ($i + 1) . "'>Fin : </label><input id='fin" . ($i + 1) . "' type='time' name='fin" . ($i + 1) . "' required> <label for='coupure" . ($i + 1) . "'>Coupure : </label><input id='coupure" . ($i + 1) . "' type='time' name='coupure" . ($i + 1) . "' required></li>";
                            }
                        }

                        ?>
                        <input type="submit" value="Ajouter">
                    </ul>
                </form>
            </div>
        </div>
        <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="ajoutSociete.php">retour</a>
        <?php
    }
    else {
        ?>
        <div class="interface">
            <form action="ajoutSociete.php" method="GET">
                <div class="input">
                    <label for="nom">Nom : </label>
                    <input id="nom" type="text" name="nom" required>
                </div>
                <div class="input">
                    <label for="nombre">Nombre de coupure : </label>
                    <input id="nombre" type="tel" name="nombre" required>
                </div>
                <input type="submit" value="Suivant">
            </form>
        </div>
        <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../accueil.php">retour</a>
        <?php
    }

    ?>

    <script type="text/javascript">
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
        }
    </script>
    </body>
    </html>
<?php } ?>