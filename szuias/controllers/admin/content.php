<?php

/** 
 * 管理员内容管理页面控制器
 * @author shonenada
 *
 */

use \Model\User;
use \Model\Menu;
use \Model\Article;
use \Model\Category;

return array(
    "export" => function($app) {
        
        // 渲染内容管理界面
        $app->get("/admin/content(/menu/:mid)", function($mid=null) use($app) {
            $pagesize = $app->config('pagesize');
            $page = $app->request->get('page');
            if (empty($page)) {
                $page = 1;
            }
            if (empty($mid)) {
                $focus_menu = Menu::get_first_menu();
            }
            else {
                $focus_menu = Menu::find($mid);
            }
            if ($focus_menu->is_parent()){
                $focus_sub_menu = $focus_menu->sub_menus->first();
            }
            else {
                $focus_sub_menu = $focus_menu;
                $focus_menu = $focus_menu->parent;
            }
            $c_mid = $focus_sub_menu->id;
            $now = time();
            $admin_menus = Menu::list_admin_menus();
            $categories = Category::all();
            $admin_list = User::all();
            $artilce_pager = Article::paginate($page, 20);
            $total = $artilce_pager->count();
            $pager = array('current' => $page, 'nums' => ceil($total / 20));
            if ($focus_menu->type == 1) {
                // 单页
                return $app->render("admin/single_page.html", get_defined_vars());
            }
            else {
                return $app->render("admin/content.html", get_defined_vars());
            }
        })->conditions(array('mid' => '\d+'));

        $app->get('/admin/content/menu/:id/create', function($menu_id) use ($app) {
            if (empty($menu_id)) {
                $focus_menu = Menu::get_first_menu();
            }
            else {
                $focus_menu = Menu::find($menu_id);
            }
            if ($focus_menu->is_parent()){
                $focus_sub_menu = $focus_menu->sub_menus->first();
            }
            else {
                $focus_sub_menu = $focus_menu;
                $focus_menu = $focus_menu->parent;
            }
            $post_menu = Menu::find($menu_id);
            $admin_menus = Menu::list_admin_menus();
            $timestamp = $_SESSION['add_timestamp'] = time() * 10000 + rand(0, 9999);
            return $app->render('admin/content_create.html', get_defined_vars());
        })->conditions(array('id' => '\d+'));

    }
);
