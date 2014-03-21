<?php

/** 
 * 文件管理
 * @author shonenada
 *
 */

return array(
    "export" => function($app) {
        $app->get('/file/fmanager', function() use ($app){
            $dir_name = trim($app->request->get('dir'));
            $path = trim($app->request->get('path'));
            $order = trim($app->request->get('order'));
            $result = Utils::filemanager($dir_name ,$path , $order);
            return json_encode($result);
        });
    }
);
