<?php

use \Twig_SimpleFunction;

$date_difference = new Twig_SimpleFunction('date_difference', function ($start, $end) {
    return $end->getTimestamp() - $start->getTimestamp();
});

return array(
    $date_difference,
);