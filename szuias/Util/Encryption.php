<?php

/**
 * Encryption
 * @author shonenada
 *
 */

namespace Util;

class Encryption {

    static public function generateToken($ip, $now, $salt){
        $now = $now->format('Y-m-d H:i:s');
        return md5 ("{$now}{{$salt}}{$ip}");
    }

}