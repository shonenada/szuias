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

        array($everyone, "/captcha", array('GET')),

        array($everyone, "/article/list", array('GET')),
        array($everyone, "/article/\d+", array('GET')),
        array($everyone, "/menu/\d+/list", array('GET')),
        array($everyone, "/menu/\d+/show", array('GET')),

        array($everyone, "/admin/signin", array('GET', 'POST')),
        array($everyone, "/admin/signout", array('GET', 'POST')),

        array($administrator, "/admin", array('GET')),

        array($administrator, "/file/fmanager", array('GET')),
        array($administrator, "/file/upload", array('POST')),

        array($administrator, "/admin/profile", array('GET')),
        array($administrator, "/admin/profile/edit", array('GET', 'POST')),
        array($administrator, "/admin/profile/password", array('GET', 'POST')),

        array($administrator, "/admin/data", array('GET')),
        array($administrator, "/admin/data/(backup|recover)", array('GET', 'POST')),
        array($administrator, "/admin/data/delete", array('POST')),

        array($administrator, "/admin/setting", array('GET')),
        array($administrator, "/admin/setting/save", array('POST')),

        array($administrator, "/admin/account", array('GET')),
        array($administrator, "/admin/account/(create|delete|reset)", array('POST')),

        array($administrator, "/admin/menu", array('GET')),
        array($administrator, "/admin/menu/(save|(\d+?)/delete)", array('POST')),

        array($administrator, "/admin/category", array('GET')),
        array($administrator, "/admin/category/(save|(\d+?)/delete)", array('POST')),

        array($administrator, "/admin/content", array('GET')),
        array($administrator, "/admin/content/menu/\d+", array('GET')),
        array($administrator, "/admin/content/menu/\d+/search", array('POST')),
        array($administrator, "/admin/content/menu/\d+/create", array('GET', 'POST')),
        array($administrator, "/admin/content/\d+/edit", array('GET', 'POST')),
        array($administrator, "/admin/content/\d+/delete", array('POST')),
        array($administrator, "/admin/content/\d+/top/(create|delete)", array('POST')),
        array($administrator, "/admin/content/\d+/hide/(create|delete)", array('POST')),
    ),
    "deny" => array(
    )
);
