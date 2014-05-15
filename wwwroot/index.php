<?php

/** 定义常量 **/
// 程序所在的目录绝对路径
define ("DOCROOT", realpath(__DIR__ . "/../") . DIRECTORY_SEPARATOR);

// 依赖库路径，依赖库在 composer.json 中添加
define ("PKGROOT", realpath(DOCROOT . "vendor/") . DIRECTORY_SEPARATOR);

// 程序自身库
define ("APPROOT", realpath(DOCROOT . "szuias/") . DIRECTORY_SEPARATOR);

// web 根目录
define ("WEBROOT", realpath(DOCROOT . "wwwroot/") . DIRECTORY_SEPARATOR);

if (!defined('START_TIME'))
    define('START_TIME', microtime(TRUE));

// 引入依赖库
require(PKGROOT . "autoload.php");

// 加载 app 工厂函数
require_once(APPROOT . "app.php");

// 设置配置文件。
$configs = array(
    WEBROOT . 'development.conf.php',
);

// 创建 app
$app = createApp($configs);

// 运行网站
$app->run();
