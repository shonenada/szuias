<?php

/** 
 * 菜单管理页面
 * @author shonenada
 *
 */

use \Model\User;
use \Model\Menu;

return array(
    "export" => function($app) {

        // 渲染菜单管理页面
        $app->get("/admin/menu", function() use($app) {
            $menus = Menu::all();
            $app->render("admin/menu.html", get_defined_vars());
        });

    }
);
