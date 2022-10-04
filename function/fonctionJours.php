<?php

function differenceJours($jour1, $jour2): int {
    return round(abs(strtotime($jour1) - strtotime($jour2)) / (60 * 60 * 24));
}
