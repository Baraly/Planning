<!DOCTYPE html>
<html lang="fr">
<head>
    <style>
        table {
            font-size: 20px;
            border-collapse: collapse;
            text-align: center;
        }

        td {
            border: 1px solid black;
            padding: 0 8px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
<?php
$bdd = new PDO('mysql:host=91.234.194.113;dbname=cp1699398p07_test;charset=utf8','cp1699398p07_admin','sh)q.sz1j7Ry');

include_once '../../../../function/fonctionMois.php';
include_once '../../../../function/fonctionHeures.php';

$idUser = $_GET['idUser'];
$mois = $_GET['mois'];
$annee = $_GET['annee'];

$donnees = $bdd->query("SELECT * FROM User WHERE id = '$idUser'")->fetch();

?>
<h1 style="font-size: 26px">Relevé des heures de travail (version administrateur)</h1>
<hr style="width: 90%">
<h2>Employé : <?= $donnees['genre'] ?> <?= $donnees['nom'] ?> <?= $donnees['prenom'] ?></h2>

<?php

$listeMoisAnnee = $bdd->query("SELECT MONTH(datage) AS mois, YEAR(datage) AS annee FROM Horaire WHERE idUser = '$idUser' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle) GROUP BY MONTH(datage), YEAR(datage) ORDER BY YEAR(datage)");

$first = true;

while ($historique = $listeMoisAnnee->fetch()) {
    $nomVariable = 'mois' . (int)$historique['mois'] . "_annee" . (int)$historique['annee'];

    if(isset($_POST[$nomVariable])) {

        $request = $bdd->query("SELECT * FROM Horaire WHERE MONTH(datage) = '" . (int)$historique['mois'] . "' AND YEAR(datage) = '" . (int)$historique['annee'] . "' AND idUser = '$idUser' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle) ORDER BY datage");

        if(!$first)
            echo "<div class='page-break'></div>";
        ?>
        <p style="font-size: 22px; margin: 0; padding: 0">Mois : <?= mois((int)$historique['mois']) ?> <?= (int)$historique['annee'] ?></p>

        <div style="width: 100%; text-align: center; margin-top: 10px; margin-bottom: 20px">
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
                $i = 1;
                $nbHeureTotale = 0;
                $nbDecouchage = 0;

                while ($donnees = $request->fetch()) {
                    ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= date('d/m/Y', strtotime($donnees['datage'])) ?></td>
                        <td><?= date('H:i', strtotime($donnees['hDebut'])) ?></td>
                        <?php
                        if ($donnees['hFin'] == null)
                            echo "<td> / </td>";
                        else
                            echo "<td>" . date('H:i', strtotime($donnees['hFin'])) . "</td>";
                        ?>
                        <td><?php
                            if ((int)date('H', strtotime($donnees['coupure'])) > 0)
                                echo date('H', strtotime($donnees['coupure'])) . "h" . date('i', strtotime($donnees['coupure']));
                            elseif ((int)date('i', strtotime($donnees['coupure'])) > 0)
                                echo date('i', strtotime($donnees['coupure'])) . 'min';
                            else
                                echo " / ";
                            ?>
                        </td>
                        <?php
                        if ($donnees['hFin'] == null) {
                            echo "<td> Non Calculé </td>";
                            $nbHeureTotale += 0;
                        } else {
                            $heureJourneeSecondes = differenceHeuresEnSecondes($donnees['hDebut'], $donnees['hFin']);

                            $tempsTravailleSecondes = $heureJourneeSecondes - getHeureEnSeconde($donnees['coupure']);

                            echo "<td>" . date('H', strtotime(getSecondeEnHeure($tempsTravailleSecondes))) . "h" . date('i', strtotime(getSecondeEnHeure($tempsTravailleSecondes))) . "</td>";

                            $nbHeureTotale += ((int)date('H', strtotime(getSecondeEnHeure($tempsTravailleSecondes))) * 60 + (int)date('i', strtotime(getSecondeEnHeure($tempsTravailleSecondes))));
                        }
                        ?>
                        <td><?php if ($donnees['decouchage']) {
                                echo "+1";
                                $nbDecouchage++;
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $i++;
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
        </div>
        <?php

        $first = false;
    }
}
?>
</body>
</html>