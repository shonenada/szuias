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

        $app->post('/file/upload', function() use ($app) {
            $dir_name = trim($app->request->get('dir'));
            $timestamp = $app->request->get('timestamp');
            if (empty($timestamp) || !is_numeric($timestamp)){
                return json_encode(array('error' => false, 'message' => '关联出错！'));
            }
            $result = Utils::upload($dir_name, $timestamp, $size = 5000000);
            return json_encode($result);
        });
    }
);
