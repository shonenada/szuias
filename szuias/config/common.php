<?php
/**
 * 网站基本信息配置
 * @author shonenada
 *
 */

return array(
    // 网站名
    'name' => 'szuias',
    // 每页默认记录数
    'pagesize' => 20,
    // 视图引擎
    'view' => new \Slim\Views\Twig(),
    // 视图模版目录
    'templates.path' => APPROOT. '/templates',
    // 开启 Cookie 加密
    'cookies.encrypt' => true,
    // 设置 Cookie 时间
    'cookies.lifetime' => '10 days',
    // Cookie 加密方式
    'cookies.cipher' => MCRYPT_RIJNDAEL_256,
    // Cookie 加密模式
    'cookies.cipher_mode' => MCRYPT_MODE_CBC,
    'cookies.httponly' => true,
    // HTTP 版本，使用 1.1 允许 PUT、DELETE 等 HTTP 方法。
    'http.version' => '1.1',

    'translation.default.code' => 'zh',
);
