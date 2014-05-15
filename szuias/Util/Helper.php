<?php

namespace Util;

class Helper {

    static public function execTime ($precision, $untilTimestamp=null) {
        $untilTimestamp = $untilTimestamp ? $untilTimestamp : time();
        $wastage = microtime(true) - START_TIME;
        return round($wastage*1000, 2);
    }

}