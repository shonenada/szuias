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
        $app->get("/admin/content(/:mid)", function($mid=null) use($app) {
            if (empty($mid)) {
                $focusMenu = Menu::get_first_menu();
            }
            else {
                $focusMenu = Menu::find($mid);
            }
            $listable = Menu::get_listable_menus();
            return $app->render("admin/content.html", get_defined_vars());
        })->conditions(array('mid' => '\d+'));

    }
);
