<?php

/**
 * 权限表
 * @author
 * 
 * 使用说明：
 *  1、use 角色类
 *  2、实例化每一个角色类
 *  3、在 allow 数组中添加 allow 规则：array(角色实例, 路由规则, HTTP 方法)
 *  4、在 deny 数组中添加 deny 规则：array(角色实例, 路由规则, HTTP 方法)
 *
 */

use RBAC\Roles\EveryOne;
use RBAC\Roles\NormalUser;
use RBAC\Roles\Administrator;

$everyone = new EveryOne();
$normal = new NormalUser();
$administrator = new Administrator();

return array(
    "allow" => array(
        array($everyone, "/", "*"),
        array($everyone, "/captcha", "*"),
        array($everyone, "/article/list", "*"),
        array($everyone, "/article/\d+", "*"),

        array($everyone, "/admin/signin", "*"),

        array($administrator, "/admin", "*"),
        array($administrator, "/admin/menu", "*"),
        array($administrator, "/admin/content", "*"),
    ),
    "deny" => array(
    )
);
