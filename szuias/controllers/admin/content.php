<?php

/** 
 * 管理员登录页面控制器
 * @author shonenada
 *
 */

use \Model\User;
use \Model\Menu;

return array(
    "export" => function($app) {

        // 渲染管理员首页
        $app->get("/admin/content", function() use($app) {
            $menus = Menu::all();
            $app->render("admin/content.html", get_defined_vars());
        });

    }
);
