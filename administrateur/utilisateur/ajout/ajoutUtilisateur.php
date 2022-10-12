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
    <title>Ajouter un utilisateur</title>
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

include_once '../../../function/bdd.php';

?>


<div style="margin-bottom: 20px">
    <a href="../../accueil.php" style="color: black; text-decoration: none; display: inline-block">
        <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
        <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
    </a>
</div>

<p style="font-size: 24px; display: inline-block; margin: 0;">Création d'un utilisateur</p>

<div class="interface">
    <?php

    if (isset($_GET['error'])) {
        $message = "";
        if($_GET['error'] == 'Email')
            $message = "Un utilisateur possède déjà cette adresse e-mail !";
        elseif($_GET['error'] == 'Code')
            $message = "Un utilisateur possède déjà cette clé personnelle !";
        elseif($_GET['error'] == 'BDD')
            $message = "La base de données à refusé de créer cet utilisateur !";
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
                        L'utilisateur a bien été ajouté !
                    </p>
                    <a href="#" onclick="closePopup()">OK parfait !</a>
                </div>
            </div>
        </div>
        <?php
    }

    ?>
        <form action="ajoutUtilisateurPost.php" method="POST">
            <div class="form" style="margin: 0; padding: 20px 20px;">
                <div>
                    <label for="nom">Nom :</label>
                    <input type="text" name="nom" id="nom" <?php if(isset($_GET['nom'])) echo "value='" . $_GET['nom'] . "'"; ?> required>
                </div>
                <div>
                    <label for="prenom">Prénom :</label>
                    <input type="text" name="prenom" id="prenom" <?php if(isset($_GET['prenom'])) echo "value='" . $_GET['prenom'] . "'"; ?> required>
                </div>
                <div>
                    <label for="mail">Email :</label>
                    <input type="email" name="mail" id="mail" <?php if(isset($_GET['mail'])) echo "value='" . $_GET['mail'] . "'"; ?> required>
                </div>
                <div>
                    <label for="tel">Tel :</label>
                    <input type="tel" name="tel" id="tel" <?php if(isset($_GET['tel'])) echo "value='" . $_GET['tel'] . "'"; ?>>
                </div>
                <div>
                    <label for="genre">Genre :</label>
                    <select name="genre" id="genre" required>
                        <?php

                        $listeGenre = ['M', 'Mr', 'Mlle', 'Mme'];

                        for($i = 0; $i < 4; $i++) {
                            if(isset($_GET['genre']) and $_GET['genre'] == $listeGenre[$i])
                                echo "<option value='" . $listeGenre[$i] . "' selected>" . $listeGenre[$i] . "</option>";
                            else
                                echo "<option value='" . $listeGenre[$i] . "'>" . $listeGenre[$i] . "</option>";
                        }

                        ?>
                        <option value="">Rien</option>
                    </select>
                </div>
                <div>
                    <label for="code">Code :</label>
                    <input type="tel" name="code" id="code" minlength="6" maxlength="6" <?php if(isset($_GET['code'])) echo "value='" . $_GET['code'] . "'"; ?> required/>
                </div>
                <div>
                    <label for="societe">Société :</label>
                    <select name="societe" id="societe">
                        <option value="null">aucune</option>
                        <?php

                        $listeSociete = $bdd->query("SELECT nomSociete, id FROM Societe");

                        while ($donnees = $listeSociete->fetch()) {
                            if(isset($_GET['societe']) and $_GET['societe'] == $donnees['id'])
                                echo "<option value='" . $donnees['id'] . "' selected>" . strtoupper($donnees['nomSociete']) . "</option>";
                            else
                                echo "<option value='" . $donnees['id'] . "'>" . strtoupper($donnees['nomSociete']) . "</option>";
                        }

                        ?>
                    </select>
                </div>
                <div>
                    <input type="submit" value="Enregistrer">
                </div>
            </div>
        </form>
</div>
<a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../../accueil.php">retour</a>

<script type="text/javascript">
    function closePopup() {
        document.getElementById("overlay").style.display = "none";
    }
</script>
</body>
</html>
<?php } ?>