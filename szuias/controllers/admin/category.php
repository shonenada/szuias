<?php

/** 
 * 分类管理页面控制器
 * @author shonenada
 *
 */


use Model\Menu;
use Model\Category;


return array(
    "export" => function($app) {

        $app->get('/admin/category', function () use ($app) {
            $menus = Menu::get_by_types(array(2));
            return $app->render('admin/category.html', get_defined_vars());
        });

        $app->post('/admin/category/save', function() use ($app) {
            $post = urldecode($app->request->params('menus'));
            $info = json_decode($post, true);
            $currentUser = $app->environment['user'];

            foreach ($info as $one) {
                $menu = Menu::find($one['mid']);
                $menu->classify = $one['classify'];
                $menu->save();
                foreach($one['categories'] as $ca) {
                    if (empty($ca['cid'])) {
                        $category = new Category();
                        $category->menu = $menu;
                        $category->creator = $currentUser;
                    }
                    else {
                        $category = Category::find($ca['id']);
                    }
                    $category->title = $ca['title'];
                    $category->sort = $ca['sort'];
                    $category->save();
                }
            }

            return json_encode(array('success' => true, 'info' => '保存成功'));
        });

    }
);
