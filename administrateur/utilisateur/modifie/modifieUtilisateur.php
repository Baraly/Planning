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
            top : 110px;
            bottom: 5%;
            left: 20px;
            right: 20px;
        }

        .form {
            font-size: 22px;
        }

        .form > div {
            margin: 10px 0;
        }

        input[type="text"], input[type="email"], input[type="tel"], select, input[type="submit"] {
            font-size: 22px;
            border-radius: 10px;
            border: 1px solid black;
            padding: 1px 8px;
        }

        select {
            border: none;
        }

        input[type="email"] {
            width: 280px;
        }

        input[type="submit"] {
            margin: 20px 80px;
            background-color: royalblue;
            color: white;
            border-radius: 8px;
            box-shadow: none;
            border: none;
            padding: 4px 8px;
            cursor: pointer;
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

<p style="font-size: 24px; display: inline-block; margin: 0;">Utilisateur N° <?= $_GET['idUser'] ?> - Modification des informations</p>

<div class="interface">
    <?php

    if (isset($_GET['error'])) {

        $message = "";
        if ($_GET['error'] == "Email")
            $message = "L'adresse email est déjà utilisée par un autre utilisateur";
        elseif ($_GET['error'] == "BDD")
            $message =  "La base de donnée a refusé les requêtes";
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
    ?>
        <form action="modifieUtilisateurPost.php?idUser=<?= $_GET['idUser'] ?>" method="POST">
            <div class="form" style="margin: 0; padding: 20px 20px;">
                <div>
                    <label for="nom">Nom :</label>
                    <input type="text" name="nom" id="nom" value="<?= $userInfo['nom'] ?>" required>
                </div>
                <div>
                    <label for="prenom">Prénom :</label>
                    <input type="text" name="prenom" id="prenom" value="<?= $userInfo['prenom'] ?>" required>
                </div>
                <div>
                    <label for="email">Email :</label>
                    <input type="email" name="email" id="email" value="<?= $userInfo['email'] ?>" required>
                </div>
                <div>
                    <label for="tel">Tel :</label>
                    <input type="tel" name="tel" id="tel" value="<?= $userInfo['telephone'] ?>">
                </div>
                <div>
                    <label for="genre">Genre :</label>
                    <select name="genre" id="genre" required>
                        <?php

                        $listeGenre = ['M', 'Mr', 'Mlle', 'Mme'];

                        $rien = true;

                        for($i = 0; $i < 4; $i++) {
                            if ($userInfo['genre'] == $listeGenre[$i]) {
                                $rien = false;
                                echo "<option value='" . $listeGenre[$i] . "' selected>" . $listeGenre[$i] . "</option>";
                            }
                            else
                                echo "<option value='" . $listeGenre[$i] . "'>" . $listeGenre[$i] . "</option>";
                        }

                        if ($rien)
                            echo "<option value='' selected>Rien</option>";
                        else
                            echo "<option value=''>Rien</option>";
                        ?>
                    </select>
                </div>
                <div>
                    <label for="societe">Société :</label>
                    <select name="societe" id="societe">
                        <?php

                        $listeSociete = $bdd->query("SELECT id, nomSociete FROM Societe");
                        $userSociete = $bdd->query("SELECT nomSociete FROM User, Societe WHERE User.idSociete = Societe.id AND User.id = '"  . $userInfo['id'] . "'")->fetch();

                        $rien = true;

                        while ($donnees = $listeSociete->fetch()) {
                            if ($userSociete['nomSociete'] == $donnees['nomSociete']) {
                                $rien = false;
                                echo "<option value='" . $donnees['id'] . "' selected>" . strtoupper($donnees['nomSociete']) . "</option>";
                            }
                            else
                                echo "<option value='" . $donnees['id'] . "'>" . strtoupper($donnees['nomSociete']) . "</option>";
                        }

                        if ($rien)
                            echo "<option value='null' selected>aucune</option>";
                        else
                            echo "<option value='null'>aucune</option>";

                        ?>
                    </select>
                </div>
                <div>
                    <label for="preferences">Préférences :</label>
                    <select name="preferences" id="preferences">
                        <?php

                        if ($bdd->query("SELECT preferenceEmail FROM User WHERE preferenceEmail = 1 AND id = '" . $_GET['idUser'] . "'")->fetch()) {
                            echo "<option value='1' selected>accepte les mails</option>";
                            echo "<option value='0'>refuse les mails</option>";
                        }
                        else {
                            echo "<option value='1'>accepte les mails</option>";
                            echo "<option value='0' selected>refuse les mails</option>";
                        }

                        ?>
                    </select>
                </div>
                <div>
                    <label for="desing">Nouveau design :</label>
                    <select name="desing" id="desing">
                        <?php

                        if ($bdd->query("SELECT ancienPlanning FROM User WHERE ancienPlanning = 1 AND id = '" . $_GET['idUser'] . "'")->fetch()) {
                            echo "<option value='1' selected>refuse les changements</option>";
                            echo "<option value='0'>accepte les changements</option>";
                        }
                        else {
                            echo "<option value='1'>refuse les changements</option>";
                            echo "<option value='0' selected>accepte les changements</option>";
                        }

                        ?>
                    </select>
                </div>
                <div>
                    <input type="submit" value="Mettre à jour">
                </div>
            </div>
        </form>
        <?php
    }
    ?>
</div>
<a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../index.php?idUser=<?= $_GET['idUser'] ?>">retour</a>

<script type="text/javascript">
    function closePopup() {
        document.getElementById("overlay").style.display = "none";
    }
</script>
</body>
</html>
<?php } ?>