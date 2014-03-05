<?php

/**
 * 定义系统入口工厂函数
 * @author shonenada
 *
 */

use \Utils;

// 加载扩展函数
require_once(APPROOT . "extensions.php");

// 导入网站配置文件
$config = require_once(APPROOT . 'config/config.php');

// 加载所有控制器
// key: 控制器名称
// value: 控制器路径，所有控制防放置于 controllers/ 目录中。
// 请不要使用函数自动读取 controllers/ 目录中
// 的控制器，Explicit is better than impliccit. (Thanks bcho)
$controllers = array (
    'home_app' => 'master',
);

// 系统入口工厂函数
function create_app ($config_files=array()) {
    if(!is_array($config_files))
        exit('Config ciles are not array.');

    // 初始化 app
    $app = new Slimx();

    // 使用全局 config 变量
    global $config;

    // 载入配置
    $app->config($config);

    // 读取用户自定义的配置
    foreach($config_files as $cfil)
        $app->config(require_once($cfil));

    // 安装钩子
    setup_hooks($app);

    // 安装 Twig 视图引擎
    setup_views($app);

    // 注册控制
    global $controllers;
    Utils::registerControllers($app, $controllers);

    return $app;
}