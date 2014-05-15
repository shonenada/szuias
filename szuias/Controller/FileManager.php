<?php

namespace Controller;

class FileManager extends \Controller\Base {

    static public $url = '/file/manager';

    static public function get () {
        $dir_name = trim(self::$request->get('dir'));
        $path = trim(self::$request->get('path'));
        $order = trim(self::$request->get('order'));
        $result = \Util\FileManager::manager($dir_name ,$path , $order);
        return json_encode($result);
    }

}
