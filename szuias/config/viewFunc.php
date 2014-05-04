<?php

use \Twig_SimpleFunction;
use \GlobalEnv;

$date_difference = new Twig_SimpleFunction('date_difference', function ($start, $end) {
    return $end->getTimestamp() - $start->getTimestamp();
});

$exec_time = new Twig_SimpleFunction('exec_time', function ($precision, $untilTimestamp=null) {
    $untilTimestamp = $untilTimestamp ? $untilTimestamp : time();
    $wastage = microtime(true) - START_TIME;
    return round($wastage*1000, 2);
});

$trans = new Twig_SimpleFunction('trans', function ($trans_id) {
    $app = GlobalEnv::get('app');
    $lang = $app->getCookie('lang');
    if ($lang == null) {
        $lang = 'zh';
    }
    $message = require(APPROOT . 'translations/' . $lang . '/message.php');
    if (!array_key_exists($trans_id, $message)) {
        return $trans_id;
    }
    return $message[$trans_id];
});

return array(
    $date_difference,
    $exec_time,
    $trans,
);