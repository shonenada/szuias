<?php

/**
 * 权限表
 * @author shonenada
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
        array($everyone, "/", array('GET')),

        array($everyone, "/lang", array('GET')),
        array($everyone, "/search", array('POST')),
        array($everyone, "/captcha", array('GET')),

        array($everyone, "/article/list", array('GET')),
        array($everyone, "/article/\d+", array('GET')),
        array($everyone, "/menu/\d+/list", array('GET')),
        array($everyone, "/menu/\d+/show", array('GET')),

        array($everyone, "/admin/signin", array('GET', 'POST')),
        array($everyone, "/admin/signout", array('GET', 'POST')),

        array($normal, "/admin", array('GET')),

        array($normal, "/file/manager", array('GET')),
        array($normal, "/file/upload", array('POST')),

        array($normal, "/admin/profile", array('GET')),
        array($normal, "/admin/profile/edit", array('GET', 'POST')),
        array($normal, "/admin/profile/password", array('GET', 'POST')),

        array($normal, "/admin/setting", array('GET')),
        array($normal, "/admin/setting/save", array('POST')),

        array($normal, "/admin/menu", array('GET')),
        array($normal, "/admin/menu/\d+", array('GET')),
        array($normal, "/admin/menu/create", array('GET', 'POST')),
        array($normal, "/admin/menu/\d+/create", array('GET', 'POST')),
        array($normal, "/admin/menu/save", array('POST')),
        array($normal, "/admin/menu/\d+/edit", array('GET', 'POST')),
        array($normal, "/admin/menu/\d+/delete", array('POST')),

        array($normal, "/admin/category", array('GET')),
        array($normal, "/admin/category/save", array('POST')),
        array($normal, "/admin/category/delete", array('POST')),

        array($normal, "/admin/content", array('GET')),
        array($normal, "/admin/content/save", array('POST')),
        array($normal, "/admin/content/menu/\d+", array('GET')),
        array($normal, "/admin/content/menu/\d+/search", array('POST')),
        array($normal, "/admin/content/menu/\d+/create", array('GET', 'POST')),
        array($normal, "/admin/content/\d+/edit", array('GET', 'POST')),
        array($normal, "/admin/content/\d+/delete", array('POST')),
        array($normal, "/admin/content/\d+/top/(create|delete)", array('POST')),
        array($normal, "/admin/content/\d+/hide/(create|delete)", array('POST')),

        array($normal, "/admin/account", array('GET')),
        array($normal, "/admin/account/(create|delete|reset|permission|permission/save)", array('POST')),

        array($normal, "/admin/data", array('GET')),
        array($normal, "/admin/data/(backup|recover)", array('GET', 'POST')),
        array($normal, "/admin/data/delete", array('POST')),

    ),
    "deny" => array(
    )
);
