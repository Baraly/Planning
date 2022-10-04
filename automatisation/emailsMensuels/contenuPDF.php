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
    </style>
</head>
<body>
<h1>Relevé des heures de travail : <?= mois($mois) ?> <?= $annee ?></h1>
<hr style="width: 90%">
<h2>Employé : <?= $user['genre'] ?> <?= $user['nom'] ?> <?= $user['prenom'] ?></h2>

<div style="width: 100%; text-align: center; margin-top: 50px">
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
</body>
</html>