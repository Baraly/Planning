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
        <title>Historique des évènements</title>
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
            }

            .info a {
                text-decoration: none;
                color: black;
                margin-bottom: 3px;
                display: inline-block;
            }

            .name > p {
                margin: 0;
                padding: 0;
            }

            .info {
                padding: 4px 20px;
                border: 1px solid black;
                text-align: left;
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
                list-style-type: none;
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

    include_once '../../function/bdd.php';

    ?>


    <div style="margin-bottom: 20px">
        <a href="../accueil.php" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <p style="font-size: 24px; display: inline-block; margin: 0;">Historique des évènements</p>

    <div class="interface">

        <div style="display: grid; grid-template-columns: 1fr 3fr; grid-gap: 60px; margin: 0 3%; position: absolute; right: 0; left: 0; top: 0; bottom: 150px;">
            <div>
                <form action="historique.php" method="get" style="border-radius: 10px; box-shadow: 0 0 5px 5px lightgrey; padding: 20px; display: inline-block">
                    <label for="priorite">Priorité :</label>
                    <select id="priorite" name="priorite" style="font-size: 20px">
                        <option value="aucune" <?php if(isset($_GET['priorite']) and $_GET['priorite'] == "aucune") echo "selected" ?>>aucune</option>
                        <option value="1" <?php if(isset($_GET['priorite']) and $_GET['priorite'] == '1') echo "selected" ?>>important</option>
                        <option value="0" <?php if(isset($_GET['priorite']) and $_GET['priorite'] == '0') echo "selected" ?>>pas important</option>
                    </select>

                    <br><br>

                    <label for="vu">Pas encore vu :</label>
                    <input type="checkbox" name="vu" value="1" id="vu" <?php if(isset($_GET['vu']) and $_GET['vu'] == 1) echo "checked" ?>>

                    <br><br>

                    <div style="text-align: center">
                        <input type="submit" value="Mettre à jour" style="font-size: 20px; background-color: royalblue; color: white; border: none; border-radius: 10px; padding: 4px 14px">
                    </div>
                </form>
            </div>

            <div class="info">
                <div>
                    <ul>
                        <?php

                        $rien = true;

                        $jourPrecedent = "";

                        $priorite = -1;

                        $sql = "SELECT * FROM Evenement ";

                        if(isset($_GET['priorite'])){
                            if ($_GET['priorite'] != "aucune")
                                $sql .= "WHERE important = '" . $_GET['priorite'] . "' ";
                        }

                        if (isset($_GET['vu'])) {
                            if (isset($_GET['priorite']) and $_GET['priorite'] != "aucune")
                                $sql .= " AND connaissance IS NULL ";
                            else
                                $sql .= " WHERE connaissance IS NULL ";
                        }

                        $sql .= "ORDER BY dateEvenement DESC";

                        $listeEvemenets = $bdd->query($sql);

                        while ($donnees = $listeEvemenets->fetch()) {
                            $rien = false;

                            if ($jourPrecedent == "") {
                                $jourPrecedent = date('d', strtotime($donnees['dateEvenement'])) . "/" . date('m', strtotime($donnees['dateEvenement'])) . "/" . date('Y', strtotime($donnees['dateEvenement']));
                                if($donnees['important'])
                                    echo "<a href='infoEvenement.php?id=" . $donnees['id'] . "&from=historique'><li>" . $jourPrecedent . " - " . $donnees['type'] . " <span style='color: white; background-color: red; padding: 3px 14px; border-radius: 10px'>important</span></li></a>";
                                else
                                    echo "<a href='infoEvenement.php?id=" . $donnees['id'] . "&from=historique'><li>" . $jourPrecedent . " - " . $donnees['type'] . "</li></a>";
                            }
                            elseif ($jourPrecedent == date('d', strtotime($donnees['dateEvenement'])) . "/" . date('m', strtotime($donnees['dateEvenement'])) . "/" . date('Y', strtotime($donnees['dateEvenement']))) {
                                if($donnees['important'])
                                    echo "<a href='infoEvenement.php?id=" . $donnees['id'] . "&from=historique'><li>" . $jourPrecedent . " - " . $donnees['type'] . " <span style='color: white; background-color: red; padding: 3px 14px; border-radius: 10px'>important</span></li></a>";
                                else
                                    echo "<a href='infoEvenement.php?id=" . $donnees['id'] . "&from=historique'><li>" . $jourPrecedent . " - " . $donnees['type'] . "</li></a>";
                            }
                            else {
                                $jourPrecedent = date('d', strtotime($donnees['dateEvenement'])) . "/" . date('m', strtotime($donnees['dateEvenement'])) . "/" . date('Y', strtotime($donnees['dateEvenement']));
                                echo "<hr>";
                                if($donnees['important'])
                                    echo "<a href='infoEvenement.php?id=" . $donnees['id'] . "&from=historique'><li>" . $jourPrecedent . " - " . $donnees['type'] . " <span style='color: white; background-color: red; padding: 3px 14px; border-radius: 10px'>important</span></li></a>";
                                else
                                    echo "<a href='infoEvenement.php?id=" . $donnees['id'] . "&from=historique'><li>" . $jourPrecedent . " - " . $donnees['type'] . "</li></a>";
                            }
                        }

                        if ($rien) {
                            echo "<p>Aucune donnée d'évènement</p>";
                        }

                        ?>
                    </ul>
                </div>
            </div>
        </div>

    </div>
    <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../accueil.php">retour</a>
    </body>
    </html>
<?php } ?>