<?php

namespace Controller;

class FileUpload extends \Controller\Base {

    static public $url = '/file/upload';

    static public function post () {
        $dir_name = trim(self::$request->get('dir'));
        $timestamp = self::$request->get('timestamp');
        if (empty($timestamp) || !is_numeric($timestamp)){
            return json_encode(array('error' => false, 'message' => '关联出错！'));
        }
        $result = \Util\Upload::upload($dir_name, $timestamp, $size = 5000000);
        return json_encode($result);
    }

}
