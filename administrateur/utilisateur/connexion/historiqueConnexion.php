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
            position: absolute;
            border: 1px solid black;
            right: 340px;
            left: 300px;
            top: 0;
            bottom: 150px;
            text-align: center;
            border-radius: 10px;
            overflow: auto;
        }

        .info > div {
            position: relative;
            height: 100%;
        }

        .info hr {
            margin: 10px;
        }

        ul {
            margin: 0 0;
            padding: 0 0;
            position: absolute;
            top: 4px;
            bottom: 0;
            right: 0;
            left: 0;
        }

        li {
            /*list-style-type: none;*/
            margin: 4px 0;
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
$userConnexion = $bdd->query("SELECT * FROM Connexion WHERE idUser = '" . $_GET['idUser'] . "' ORDER BY dateConnexion DESC");

?>

<p style="font-size: 24px; display: inline-block; margin: 0;">Utilisateur N° <?= $_GET['idUser'] ?> - Historique des connexions</p>

<div class="interface">

    <div class="name">
        <p style="margin-bottom: 8px">Nom : <?= strtoupper($userInfo['nom']) ?></p>
        <p>Prénom : <?= $userInfo['prenom'] ?></p>
    </div>

    <div class="info">
        <div>
            <ul>
            <?php

            $rien = true;

            $jourPrecedent = "";

            while($donnees = $userConnexion->fetch()){
                $rien = false;

                if ($jourPrecedent == "") {
                    $jourPrecedent = date('d', strtotime($donnees['dateConnexion'])) . "/" . date('m', strtotime($donnees['dateConnexion'])) . "/" . date('Y', strtotime($donnees['dateConnexion']));
                    echo "<li><p style='position: absolute; left: 20px; display: inline-block'>" . $jourPrecedent . " " . date('H', strtotime($donnees['dateConnexion'])) . ":" . date('i', strtotime($donnees['dateConnexion'])) . ":" . date('s', strtotime($donnees['dateConnexion'])) . "</p><p style='position: absolute; right: 20px; display: inline-block'>" . $donnees['appareil'] . "</p></li>";
                }
                elseif ($jourPrecedent == date('d', strtotime($donnees['dateConnexion'])) . "/" . date('m', strtotime($donnees['dateConnexion'])) . "/" . date('Y', strtotime($donnees['dateConnexion']))) {
                    echo "<li><p style='position: absolute; left: 20px; display: inline-block'>" . $jourPrecedent . " " . date('H', strtotime($donnees['dateConnexion'])) . ":" . date('i', strtotime($donnees['dateConnexion'])) . ":" . date('s', strtotime($donnees['dateConnexion'])) . "</p><p style='position: absolute; right: 20px; display: inline-block'>" . $donnees['appareil'] . "</p></li>";
                }
                else {
                    $jourPrecedent = date('d', strtotime($donnees['dateConnexion'])) . "/" . date('m', strtotime($donnees['dateConnexion'])) . "/" . date('Y', strtotime($donnees['dateConnexion']));
                    echo "<hr>";
                    echo "<li><p style='position: absolute; left: 20px; display: inline-block'>" . date('d', strtotime($donnees['dateConnexion'])) . "/" . date('m', strtotime($donnees['dateConnexion'])) . "/" . date('Y', strtotime($donnees['dateConnexion'])) . " " . date('H', strtotime($donnees['dateConnexion'])) . ":" . date('i', strtotime($donnees['dateConnexion'])) . ":" . date('s', strtotime($donnees['dateConnexion'])) . "</p><p style='position: absolute; right: 20px; display: inline-block'>" . $donnees['appareil'] . "</p></li>";
                }
            }

            if ($rien) {
                echo "<p>Aucune donnée de connexion</p>";
            }

            ?>
            </ul>
        </div>
    </div>

    <?php } ?>
</div>
<a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../index.php?idUser=<?= $_GET['idUser'] ?>">retour</a>
</body>
</html>
<?php } ?>