<?php

use \Twig_SimpleFunction;

$date_difference = new Twig_SimpleFunction('date_difference', function ($start, $end) {
    return $end - $start;
});

return array(
    $date_difference,
);