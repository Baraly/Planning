<?php

function soustractionHeures($heure1, $heure2): string {
    $difference = strtotime($heure1) - strtotime($heure2);

    return date('H', $difference) . ":" . date('i', $difference) . ":" . date('s', $difference);
}
