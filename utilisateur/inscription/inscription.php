<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF8"/>
    <title>Inscription planning</title>
    <style>
        @font-face {
            font-family: myFirstFont;
            src: url(../../font-style/ABeeZee-Regular.ttf);
        }
        body {
            font-family: myFirstFont;
            margin-top: 10%;
            text-align: center;
            z-index: 1;
        }

        label {
            display: block;
        }

        input[type="text"], input[type="email"], input[type="tel"] {
            padding: 2% 1%;
            border: none;
            border-bottom: 1px gray solid;
            border-radius: 0;
            margin: 2% 0;
            width: 80%;
            font-size: 350%;
        }

        input[type="tel"] {
            display: inline-block;
        }

        select {
            display: inline-block;
            font-size: 350%;
            padding: 2% 2%;
            margin: 2% 0;
            border-radius: 30px;
        }

        input[type="submit"] {
            -webkit-appearance: none;
            width: 78%;
            margin-top: 20%;
            font-size: 350%;
            padding: 3% 0;
            border: none;
            border-radius: 20px;
            color: white;
            background-color: #3B326C;
            box-shadow: #3B326C 0 60px 60px -40px;
        }

        <?php

        if(isset($_GET['error'])) {
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
            font-size: 300%;
        }

        .content > a {
            display: inline-block;
            background-color: #212F3D;
            color: white;
            text-decoration: none;
            padding: 2% 4%;
            border-radius: 20px;
        }
            <?php
        }

        ?>
    </style>
</head>
    <body>

    <h1 style="font-size: 375%; margin-bottom: 20%">Inscription au planning</h1>

    <form action="inscriptionPost.php" method="GET">
        <?php
        $bdd = null;
        include_once '../../function/bdd.php';

        if (isset($_GET['error'])) {
            ?>
            <input type="text" name="nom" value="<?= $_GET['nom'] ?>" placeholder="Nom" required><br>
            <input type="text" name="prenom" value="<?= $_GET['prenom'] ?>" placeholder="Prénom" required><br>
            <?php
            if ($_GET['error'] == 'email') {
                ?>
                <div class="overlay" id="overlay">
                    <div class="subOverlay">
                        <div class="content">
                            <p>L'adresse email <span style="font-weight: bold"><?= $_GET['email'] ?></span> est déjà
                                utilisée.<br><br>
                                Si vous rencontrez des difficultés à vous connecter à votre compte, alors veuillez contacter le
                                support :
                                <a href="mailto:planning@baraly.fr">planning@baraly.fr</a>
                            </p>
                            <a href="#" onclick="closePopup()">J'ai compris</a>
                        </div>
                    </div>
                </div>
                <?php
            }
            if ($_GET['error'] == 'code') {
                ?>
                <div class="overlay" id="overlay">
                    <div class="subOverlay">
                        <div class="content">
                            <p>La clé personnelle <span style="font-weight: bold"><?= $_GET['code'] ?></span> est malheureusement déjà
                                utilisée.<br><br>
                                Si vous rencontrez des difficultés à vous connecter à votre compte, alors veuillez contacter le
                                support :
                                <a href="mailto:planning@baraly.fr">planning@baraly.fr</a>
                            </p>
                            <a href="#" onclick="closePopup()">J'ai compris</a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <input type="email" name="email" value="<?= $_GET['email'] ?>" placeholder="E-mail" required><br>
            <select name="genre" required>
                <?php
                if ($_GET['genre'] == "M")
                    echo "<option value='M' selected>M</option>";
                else
                    echo "<option value='M'>M</option>";

                if ($_GET['genre'] == "Mr")
                    echo "<option value='Mr' selected>Mr</option>";
                else
                    echo "<option value='Mr'>Mr</option>";

                if ($_GET['genre'] == "Mlle")
                    echo "<option value='Mlle' selected>Mlle</option>";
                else
                    echo "<option value='Mlle'>Mlle</option>";

                if ($_GET['genre'] == "Mme")
                    echo "<option value='Mme' selected>Mme</option>";
                else
                    echo "<option value='Mme'>Mme</option>";

                if ($_GET['genre'] == "rien")
                    echo "<option value='rien' selected>rien</option>";
                else
                    echo "<option value='rien'>rien</option>";
                ?>
            </select><br>
            <input type="tel" name="code" value="<?= $_GET['code'] ?>" minlength="6" maxlength="6"
                   placeholder="Clé personnelle" required><br>
            <label style="font-size: 315%">Pour quelle entreprise travaillez-vous :<br>
                <select name="societe" required style="font-size: 100%">
                    <?php
                    $autre = true;

                    $request = $bdd->query("SELECT * FROM Societe");
                    while ($donnee = $request->fetch()) {
                        if ($_GET['societe'] == $donnee['id']) {
                            echo "<option value='" . $donnee['id'] . "' selected>" . $donnee['nomSociete'] . "</option>";
                            $autre = false;
                        }
                        else
                            echo "<option value='" . $donnee['id'] . "'>" . $donnee['nomSociete'] . "</option>";

                    }

                    if($autre)
                        echo "<option value='autre' selected>Autre</option>";
                    else
                        echo "<option value='autre'>Autre</option>";
                    ?>
                </select>
            </label>
            <?php
        } else {
            ?>
            <input type="text" name="nom" placeholder="Nom" required><br>
            <input type="text" name="prenom" placeholder="Prénom" required><br>
            <input type="email" name="email" placeholder="E-mail" required><br>
            <select name="genre" required>
                <option value="Mr">Mr</option>
                <option value="M">M</option>
                <option value="Mlle">Mlle</option>
                <option value="Mme">Mme</option>
                <option value="rien">rien</option>
            </select>
            <br>
            <input type="tel" name="code" minlength="6" maxlength="6" placeholder="Clé personnelle" required>
            <br>
            <label style="font-size: 315%">Pour quelle entreprise travaillez-vous :<br>
                <select name="societe" required style="font-size: 100%">
                    <?php

                    $request = $bdd->query("SELECT * FROM Societe");
                    while ($donnee = $request->fetch()) {
                        echo "<option value='" . $donnee['id'] . "'>" . $donnee['nomSociete'] . "</option>";
                    }
                    ?>
                    <option value="autre">Autre</option>
                </select>
            </label>
            <?php
        }
        ?>

        <input type="submit" value="Créer mon compte">

    </form>
    <script>
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
        }
    </script>
    </body>
</html>