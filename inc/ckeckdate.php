<?php

// Fonction pour afficher toutes les dates comprises entre 2 dates (date_arrivee et date_depart)
function getDatesFromRange($start, $end)
{
    $interval = new DateInterval('P1D'); // per 1 Day
    $realEnd = new DateTime($end);
    $realEnd->add($interval);
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
    foreach ($period as $date) {
        $array[] = $date->format('Y-m-d');
    }
    return $array;
}

$tab = getDatesFromRange('2017-10-15', '2017-10-20');
echo '<pre>'; var_dump($tab); echo '</pre>';