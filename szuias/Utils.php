<?php

/**
 * 定义工具类
 * @author shonenada
 *
 */


class Utils {

    // 生成 token 静态方法
    static public function generateToken($ip, $now, $salt){
        $now = $now->format('Y-m-d H:i:s');
        return md5 ("{$now}{{$salt}}{$ip}");
    }

    // 根据控制器名请求控制器。例如：
    //     控制器为 controllers/master/home.php
    //     <?php Utils::requireController('master/home');
    static public function requireController ($controllerName) {
        return require_once (APPROOT . "controllers/{$controllerName}.php");
    }

    // 注册控制器协助函数
    static function registerControllers($app, $controllers){
        foreach ($controllers as $name => $path) {
            $controller = Utils::requireController($path);
            $app->registerController($controller);
        }
    }

}