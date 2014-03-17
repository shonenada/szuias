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
            $menus = Menu::getTopMenus();
            $app->render("admin/menu.html", get_defined_vars());
        });


        // 删除 menu
        $app->get('/admin/menu/:mid/delete', function ($mid) use($app) {
            // TODO: 联动权限表删除
            $menu = Menu::find($mid);
            if ($menu == null) {
                return json_encode(array('success' => false, 'info' => '对象不存在'));
            }
            foreach ($menu->articles as $a){
                $a->delete();
            }
            $menu->delete();
            return json_encode(array('success' => true, 'info' => '删除成功!'));
        })->condition(array('mid' => '\d+'));


        // 获取来自客户端的提交信息，更新信息。
        $app->post('/admin/menu/create', function() use($app) {
            // 从 客户端 获取 post 的信息，并进行解码
            $post = urldecode($app->request->params('menus'));
            $menus = json_decode($post, true);

            foreach ($menus as $m) {
                // $m['id'] 为空的时候表示数据库中不存在，即等待创建的数据
                if (empty($m['id'])) {
                    // 创建新对象，从数据中获取数据，并保存到数据库中
                    $firstMenu = new Menu();
                    $firstMenu->populate_from_array($m)->save();

                    foreach ($m['subMenus'] as $sub) {
                        $subMenu = new Menu();
                        $subMenu->parent = $firstMenu;  // 设置父 menu
                        $subMenu->populate_from_array($sub)->save();
                    }
                }
                // 数据库中已存在的数据，等待修改的数据
                else {
                    $menu = Menu::find($m['id']);
                    if ($menu == null){
                        return json_encode(array(
                            'success' => false,
                            'info' => '对象不存在'
                        ));
                    }
                    $menu->populate_from_array($m)->save();

                    foreach ($m['subMenus'] as $sub) {
                        if (empty($sub['id'])) {
                            $subMenu = new Menu();
                            $subMenu->parent = $menu;
                        }
                        else {
                            $subMenu = Menu::find($sub['id']);
                        }
                        $subMenu->populate_from_array($sub)->save();
                    }
                }
            }
            return json_encode(array('success' => true, 'info' => '保存成功!'));
        });

    }
);