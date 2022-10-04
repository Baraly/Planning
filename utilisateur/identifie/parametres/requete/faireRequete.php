<?php
session_start();

$bdd = null;
require_once '../../../../function/bdd.php';

if(isset($_SESSION['id']))
    $id = $_SESSION['id'];

if (!empty($_GET['idUser']) and !empty($_GET['vkey']))
    if($bdd->query("SELECT * FROM User WHERE id = '" . $_GET['idUser'] . "' AND cleSecurite = '" . $_GET['vkey'] . "'")->fetch())
        $id = $_SESSION['id'];

if(isset($id)) {
$userInfo = $bdd->query("SELECT * FROM User WHERE id ='$id'")->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF8"/>
    <title>Faire une requête</title>
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
            font-size: 325%;
            margin: 20% 10%;
            text-align: left;
        }

        form > div {
            margin-bottom: 30px;
        }

        input {
            font-size: 100%;
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
        }

        select{
            font-size: 100%;
        }

        textarea {
            font-size: 90%;
            padding: 20px;
            border-radius: 20px;
            height: 100%;
            width: 100%;
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
            font-size: 325%;
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
<h1 style="font-size: 350%">Faire une requête</h1>

<form action="faireRequetePost.php?idUser=<?= $id ?>&vkey=<?= $_GET['vkey'] ?>" method="POST">
    <div>
        <p>Nom : <?= strtoupper($userInfo['nom']) ?> <?= $userInfo['prenom'] ?></p>
    </div>
    <div>
        <div>
            <label for="type">Type de requête :</label>
        </div>
        <div>
            <select name="type" id="type" required>
                <?php

                $listeTypeRequete = ['Problème de compte', 'Modifier mon code', "Problème d'interface", 'Soumettre une remarque', 'Envoyer un message gentil ;)', 'Suspendre mon compte', 'Autre'];

                foreach ($listeTypeRequete as $type)
                    if(isset($_GET['type']) and $_GET['type'] == $type)
                        echo "<option value='$type' selected>$type</option>";
                    else
                        echo "<option value='$type'>$type</option>";

                ?>
            </select>
        </div>
    </div>
    <div style="margin-top: 5%; height: 20%">
        <div>
            <label for="message">Message :</label>
        </div>
        <div style="height: 100%">
            <textarea name="message" id="message" required><?php if(isset($_GET['message'])) echo $_GET['message']; ?></textarea>
        </div>
    </div>

    <div style="text-align: center">
        <input type="submit" value="Envoyer la requête">
    </div>

</form>

<?php
if(isset($_GET['error'])) {
    $messageError = "";

    if($_GET['error'] == 'BDD')
        $messageError = "Nous n'avons pas réussi à envoyer votre requête.<br><br>L'administrateur a été averti de cet incident !";

    elseif($_GET['error'] == 'BDD_Admin')
        $messageError = "Nous n'avons pas réussi à envoyer votre requête.<br><br>L'administrateur n'a pas pu être averti de cet incident !";

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
                    Votre requête a été envoyé avec succès !
                    <br>
                    Vous recevrez une réponse par e-mail.
                </p>
                <a href="#" onclick="closePopup()">Parfait !</a>
            </div>
        </div>
    </div>
    <?php
}
?>

<a href="../../planning.php" class="return_button">Retour</a>

<script>
    function closePopup() {
        document.getElementById("overlay").style.display = "none";
    }
</script>
</body>
</html>
<?php
}
else {
    echo "<h1 style='font-size: 325%'>Afin de pouvoir emmètre une requête, vous devez être connecté au <a href='../../../index.php' style='color: royalblue; text-decoration: none'>Planning</a>.</h1>";
}
?>