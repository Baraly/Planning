<?php
session_start();

$bdd = null;
include_once '../../function/bdd.php';

if (!isset($_SESSION['id']))
    header("location: ../index.php");
elseif ($bdd->query("SELECT nom FROM User WHERE id = '" . $_SESSION['id'] . "' AND ancienPlanning = 1")->fetch()) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF8"/>
        <title>Gestion Horaire</title>
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <style>
            body {
                margin-top: 4%;
                text-align: center;
            }

            table {
                font-size: 175%;
                border-collapse: collapse;
                text-align: center;
                margin: 0 2%;
            }

            td {
                border: 1px solid black;
                padding: 0 8px;
            }

            a {
                text-decoration: none;
            }

            a.button {
                font-size: 375%;
                border: 1px solid gray;
                border-radius: 30px;
                background-color: #212F3D;
                color: white;
                padding: 2% 2%;
            }
        </style>
    </head>
    <body>
    <h1 style="font-size: 350%">Gestion des horaires</h1>

    <?php
    include_once '../../function/fonctionHeures.php';

    $nbJoursBDD = $bdd->query("SELECT COUNT(*) AS nb FROM Horaire WHERE MONTH(datage) = '" . date('m') . "' AND YEAR(datage) = '" . date('Y') . "' AND idUser = '" . $_SESSION['id'] . "' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle)")->fetch();

    $nbJour = 23;

    if ((int)$nbJoursBDD['nb'] > $nbJour) {
        $nbJour = (int)$nbJoursBDD['nb'];
    }

    $request = $bdd->query("SELECT * FROM Horaire WHERE MONTH(datage) = '" . date('m') . "' AND YEAR(datage) = '" . date('Y') . "' AND idUser = '" . $_SESSION['id'] . "'  AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle) ORDER BY datage");
    $nbHeureTotale = 0;
    $nbDecouchage = 0;
    ?>

    <table>
        <tr>
            <td></td>
            <td>Date</td>
            <td>Heure de début</td>
            <td>Heure de fin</td>
            <td>Pause</td>
            <td>Heures travaillées</td>
            <td>Découchage</td>
        </tr>
        <?php
        for ($i = 1; $i <= $nbJour; $i++) {
            ?>
            <tr>
                <td><?= $i ?></td>
                <?php
                if ($donnees = $request->fetch()) {
                    ?>
                    <td><?= date('d/m/Y', strtotime($donnees['datage'])) ?></td>
                    <td><?= date('H:i', strtotime($donnees['hDebut'])) ?></td>
                    <td><?php
                        if ($donnees['hFin'] == null)
                            echo "";
                        else
                            echo date('H:i', strtotime($donnees['hFin']));
                        ?>
                    </td>
                    <td><?php
                        if ((int)date('H', strtotime($donnees['coupure'])) > 0)
                            echo date('H', strtotime($donnees['coupure'])) . "h" . date('i', strtotime($donnees['coupure']));
                        elseif ((int)date('i', strtotime($donnees['coupure'])) > 0)
                            echo date('i', strtotime($donnees['coupure'])) . 'min';
                        ?>
                    </td>
                    <?php
                    if ($donnees['hFin'] == null)
                        echo "<td>En cours</td>";
                    else {
                        $tempsTravailleSeconde = differenceHeuresEnSecondes($donnees['hDebut'], $donnees['hFin']);
                        // $heureTravailleStr = differenceHeures($donnees['hDebut'], $donnees['hFin']);

                        //$diffDate = new DateTime("{$heureTravailleStr}");
                        //$pause = new DateTime("{$donnees['coupure']}");

                        //$resul = $pause->diff($diffDate);

                        $heureCoupure = (int)$donnees['coupure'][0] * 10 + (int)$donnees['coupure'][1];
                        $minuteCoupure = (int)$donnees['coupure'][3] * 10 + (int)$donnees['coupure'][4];

                        $tempsTravailleSeconde -= ($heureCoupure * 3600 + $minuteCoupure * 60);

                        $tempsTravailleHeure = getSecondeEnHeure($tempsTravailleSeconde);
                        echo "<td>" . date('H', strtotime($tempsTravailleHeure)) . "h" . date('i', strtotime($tempsTravailleHeure)) . "</td>";

                        $nbHeureTotale += $tempsTravailleSeconde/60;
                    }


                    ?>
                    <td><?php if ($donnees['decouchage']) {
                            echo "+1";
                            $nbDecouchage++;
                        }
                        ?>
                    </td>
                    <?php
                } else {
                    for ($j = 0; $j < 6; $j++) {
                        echo "<td></td>";
                    }
                }
                ?>
            </tr>
        <?php }
        $heures = (int)($nbHeureTotale / 60);
        $minutes = $nbHeureTotale - $heures * 60;
        if ($heures < 10)
            $strNbHeureTotale = "0" . $heures;
        else
            $strNbHeureTotale = $heures;
        if ($minutes < 10)
            $strNbHeureTotale .= "h0" . $minutes;
        else
            $strNbHeureTotale .= "h" . $minutes;
        ?>
        <tr>
            <td style="color: white">f</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>TOTAL</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?= $strNbHeureTotale ?></td>
            <td><?= $nbDecouchage ?></td>
        </tr>
    </table>
    <div style="margin-top: 14%">
        <a class="button" style="position: relative; padding-right: 10%" href="manipulationJournee/commencer/commencerJournee.php">Menu <i
                    style="font-size: 120%; position: absolute; right: 5%" class='bx bxs-truck'></i></a>
    </div>
    <div style="margin-top: 8%">
        <a class="button" href="historique/historique.php">Historique des données</a>
    </div>
    </body>
    </html>
    <?php
}
else {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF8"/>
        <title>Gestion Horaire</title>
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <script src="function/timeout.js"></script>
        <style>
            @font-face {
                font-family: myFirstFont;
                src: url(../../font-style/ABeeZee-Regular.ttf);
            }
            body {
                font-family: myFirstFont;
                margin-top: 4%;
                text-align: center;
            }

            table {
                margin: 0;
                padding: 0;
            }

            table tbody tr {
                display: table-row;
                border-collapse: collapse;
            }

            a {
                text-decoration: none;
            }

            a.button {
                font-size: 325%;
                border: 1px solid gray;
                border-radius: 30px;
                background-color: #212F3D;
                color: white;
                padding: 2% 6%;
            }

            .card {
                margin: 3% auto;
                padding-bottom: 2%;
                background-size: auto;
                display: flex;
                flex-direction: column;
                width: 95%;
                border-radius: 30px;
                background-color: white;
                box-shadow: rgba(0, 0, 0, 0.14) 0 0 20px 20px;
            }

            .card .title {
                display: grid;
                grid-template-columns: 1fr;
                grid-template-rows: 1fr;
                color: white;
                width: 84%;
                margin: -50px auto 0;
                padding: 1% 1%;
                border-radius: 10px;
                background-image: linear-gradient(60deg, #6A919C, #1A4B58);
                box-shadow: rgba(0, 0, 0, 0.14) 0 10px 30px 0, rgb(66, 110, 122) 0 7px 10px -5px
            }

            .card p {
                margin: 2% 0 0;
            }

            .card .body {
                width: 95%;
                margin: 1% auto;
                display: inline-block;
                color: rgb(51, 51, 51);
                text-align: center;
            }

            .card table {
                width: 100%;
                display: table;
                text-align: left;
                border-collapse: separate;
                border-spacing: 30px;
            }

            .card tr {
                margin: 1% 0;
                height: 3%;
            }

        </style>
    </head>
    <body>
    <h1 style="font-size: 350%">Gestion des horaires</h1>

    <?php
    include_once '../../function/fonctionHeures.php';


    if (date('W') == 52)
        $jours = $bdd->query("SELECT * FROM Horaire WHERE (WEEK(datage) = '" . date('W') . "' AND YEAR(datage) = '" . date('Y') . "' AND WEEKDAY(datage) <> '6' AND idUser = '" . $_SESSION['id'] . "' OR WEEK(datage) = '1' AND WEEKDAY(datage) = '6' AND YEAR(datage) = '" . date('Y') . "' AND idUser = '" . $_SESSION['id'] . "') AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle)");
    else
        $jours = $bdd->query("SELECT * FROM Horaire WHERE (WEEK(datage) = '" . date('W') . "' AND YEAR(datage) = '" . date('Y') . "' AND WEEKDAY(datage) <> '6' AND idUser = '" . $_SESSION['id'] . "' OR WEEK(datage) = '" . (date('W') + 1) . "' AND WEEKDAY(datage) = '6' AND YEAR(datage) = '" . date('Y') . "' AND idUser = '" . $_SESSION['id'] . "') AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle)");

    $joursSemaine = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

    $semaine = array();
    for ($i = 0; $i < 7; $i++) {
        $semaine[$i] = NULL;
    }

    while ($donnee = $jours->fetch()) {
        for ($i = 0; $i < 7; $i++) {
            if (date('w', strtotime($donnee['datage'])) == $i) {
                $semaine[$i] = $donnee;
            }
        }
    }

    ?>

    <div style="padding: 4% 2%; text-align: center">
        <div class="card">
            <div class="title">
                <p style="text-align: center; font-size: 325%; margin: 2% 0">
                    Historique hebdomadaire
                </p>
            </div>
            <div class="body">
                <table style="font-size: 250%">
                    <thead>
                    <tr style="color: rgb(66, 110, 122)">
                        <th>Jour</th>
                        <th>Date</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Coupure</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($i = 1; $i < 6; $i++) {
                        echo "<tr><td>" . $joursSemaine[$i] . "</td>";
                        if ($semaine[$i] != NULL) {
                            echo "<td>" . date('d/m', strtotime($semaine[$i]['datage'])) . "</td>";
                            echo "<td>" . date('H:i', strtotime($semaine[$i]['hDebut'])) . "</td>";
                            if ($semaine[$i]['hFin'] != NULL) {
                                echo "<td>" . date('H:i', strtotime($semaine[$i]['hFin'])) . "</td>";
                            } else {
                                echo "<td></td>";
                            }
                            if ((int)date('H', strtotime($semaine[$i]['coupure'])) > 0)
                                echo "<td>" . date('H', strtotime($semaine[$i]['coupure'])) . "h" . date('i', strtotime($semaine[$i]['coupure'])) . "</td>";
                            elseif ((int)date('i', strtotime($semaine[$i]['coupure'])) > 0)
                                echo "<td>" . date('i', strtotime($semaine[$i]['coupure'])) . "min</td>";
                        } else {
                            echo "<td></td><td></td><td></td><td></td>";
                        }
                        echo "</tr>";
                    }
                    if ($semaine[6] != NULL) {
                        echo "<tr><td>" . $joursSemaine[6] . "</td>";
                        echo "<td>" . date('d/m', strtotime($semaine[6]['datage'])) . "</td>";
                        echo "<td>" . date('H:i', strtotime($semaine[6]['hDebut'])) . "</td>";
                        if ($semaine[6]['hFin'] != NULL) {
                            echo "<td>" . date('H:i', strtotime($semaine[6]['hFin'])) . "</td>";
                        } else {
                            echo "<td></td>";
                        }
                        if ((int)date('H', strtotime($semaine[6]['coupure'])) > 0)
                            echo "<td>" . date('H', strtotime($semaine[6]['coupure'])) . "h" . date('i', strtotime($semaine[6]['coupure'])) . "</td>";
                        elseif ((int)date('i', strtotime($semaine[6]['coupure'])) > 0)
                            echo "<td>" . date('i', strtotime($semaine[6]['coupure'])) . "min</td>";
                        echo "</tr>";
                    }

                    if ($semaine[0] != NULL) {
                        echo "<tr><td>" . $joursSemaine[0] . "</td>";
                        echo "<td>" . date('d/m', strtotime($semaine[0]['datage'])) . "</td>";
                        echo "<td>" . date('H:i', strtotime($semaine[0]['hDebut'])) . "</td>";
                        if ($semaine[0]['hFin'] != NULL) {
                            echo "<td>" . date('H:i', strtotime($semaine[0]['hFin'])) . "</td>";
                        } else {
                            echo "<td></td>";
                        }
                        if ((int)date('H', strtotime($semaine[0]['coupure'])) > 0)
                            echo "<td>" . date('H', strtotime($semaine[0]['coupure'])) . "h" . date('i', strtotime($semaine[0]['coupure'])) . "</td>";
                        elseif ((int)date('i', strtotime($semaine[0]['coupure'])) > 0)
                            echo "<td>" . date('i', strtotime($semaine[0]['coupure'])) . "min</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <hr>
                <?php
                $request = $bdd->query("SELECT * FROM Horaire WHERE MONTH(datage) = '" . date('m') . "' AND YEAR(datage) = '" . date('Y') . "' AND hFin IS NOT NULL AND idUser = '" . $_SESSION['id'] . "' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle)");
                $nbHeureTotale = 0;
                while ($donnee = $request->fetch()) {
                    $heureTravailleStr = differenceHeures($donnee['hDebut'], $donnee['hFin']);

                    $diffDate = new DateTime("{$heureTravailleStr}");
                    $pause = new DateTime("{$donnee['coupure']}");

                    $resul = $pause->diff($diffDate);

                    $nbHeureTotale += ((int)$resul->format("%H") * 60 + (int)$resul->format("%I"));
                }
                $heures = (int)($nbHeureTotale / 60);
                $minutes = $nbHeureTotale - $heures * 60;
                if ($heures < 10)
                    $strNbHeureTotale = "0" . $heures;
                else
                    $strNbHeureTotale = $heures;
                if ($minutes < 10)
                    $strNbHeureTotale .= "h0" . $minutes;
                else
                    $strNbHeureTotale .= "h" . $minutes;
                ?>
                <p style="width: 100%; font-size: 260%; text-align: left">Heures travaillées
                    : <?= $strNbHeureTotale ?></p>
            </div>
        </div>
    </div>

    <div style="position: absolute; bottom: 10%; width: 100%">
        <div>
            <a class="button" style="position: relative; padding-right: 10%" href="manipulationJournee/commencer/commencerJournee.php">Menu <i
                        style="font-size: 120%; position: absolute; right: 5%" class='bx bxs-truck'></i></a>
        </div>
        <div style="margin-top: 8%">
            <a class="button" href="historique/historique.php">Historique des données</a>
        </div>
    </div>
    </body>
    </html>

<?php }
?>