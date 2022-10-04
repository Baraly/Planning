<?php
function mois($numMois) {

    $listeMois = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

    return $listeMois[(int)$numMois-1];
}

