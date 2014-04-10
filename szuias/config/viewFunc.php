<?php

use \Twig_SimpleFunction;

$date_difference = new Twig_SimpleFunction('date_difference', function ($start, $end) {
    return $end->getTimestamp() - $start->getTimestamp();
});

$exec_time = new Twig_SimpleFunction('exec_time', function ($precision, $untilTimestamp=null) {
    $untilTimestamp = $untilTimestamp ? $untilTimestamp : time();
    $wastage = microtime(true) - START_TIME;
    return round($wastage*1000, 2);
});

return array(
    $date_difference,
    $exec_time,
);