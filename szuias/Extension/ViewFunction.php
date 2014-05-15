<?php

namespace Extension;

class ViewFunction {

    static public $functions = null;

    static private $trans = null;
    static private $execTime = null;
    static private $dateDifference = null;

    static public function init() {

        static::$trans = new \Twig_SimpleFunction('trans', function ($trans_id) {
            $app = \GlobalEnv::get('app');
            $lang = $app->getCookie('lang.code');
            if ($lang == null) {
                $lang = 'zh';
            }
            $message = require(APPROOT . 'translations/' . $lang . '/message.php');
            if (!array_key_exists($trans_id, $message)) {
                return $trans_id;
            }
            return $message[$trans_id];
        });

        static::$execTime = new \Twig_SimpleFunction('execTime', function ($precision, $untilTimestamp=null) {
            $untilTimestamp = $untilTimestamp ? $untilTimestamp : time();
            $wastage = microtime(true) - START_TIME;
            return round($wastage*1000, 2);
        });

        static::$dateDifference = new \Twig_SimpleFunction('dateDifference', function ($start, $end) {
            return $end->getTimestamp() - $start->getTimestamp();
        });

        static::$functions = array(
            static::$trans,
            static::$execTime,
            static::$dateDifference,
        );
    }

}
ViewFunction::init();
