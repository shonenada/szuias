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
            $artilce_pager = Article::paginate_with_mid($page, $pagesize, $c_mid, false, 'sort');
            $total = $artilce_pager->count();
            $now = new \DateTime();
            $admin_menus = Menu::list_admin_menus();
            $categories = Category::all();
            $admin_list = User::all();
            $pager = array('current' => $page, 'nums' => ceil($total / $pagesize));
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

        $app->post('/admin/content/menu/:id/create', function($menu_id) use ($app) {
            if ($_SESSION['add_timestamp'] != $app->request->post('timestamp')) {
                return $app->render("admin/content_create.html", get_defined_vars());
            }
            $menu = Menu::find($menu_id);
            $user = $app->environment['user'];
            if ($menu->type == 1) {
                // 单页
                $a = Article::findOneBy(array('menu' => $menu));
                if ($a != null) {
                    $info = '单页菜单只允许存在一篇文章，新增失败！';
                    return $app->render("admin/content_create.html", get_defined_vars());
                }
            }
            $article = new Article();
            $category = Category::find($app->request->post('category_id'));
            $data = array(
                'title' => $app->request->post('title'),
                'content' => $app->request->post('content'),
                'menu' => $menu,
                'category' => $category,
                'author' => $user,
                'editor' => $user,
                'edit_time' => new \DateTime($app->request->post('moditime')),
            );
            $article->populate_from_array($data)->save();
            return $app->redirect('/admin/content/menu/' . $menu_id);
        })->conditions(array('id' => '\d+'));

        $app->post('/admin/content/:aid/hide/create', function($aid) use ($app) {
            $article = Article::find($aid);
            if ($article) {
                if (!$article->is_hide) {
                    $article->hide();
                    $article->save();
                    return json_encode(array('success' => true, 'info' => '设置成功'));
                }
            }
            return json_encode(array('success' => true, 'info' => '文章不存在'));
        })->conditions(array('aid' => '\d+'));

        $app->post('/admin/content/:aid/hide/delete', function($aid) use ($app) {
            $article = Article::find($aid);
            if ($article) {
                if ($article->is_hide) {
                    $article->show();
                    $article->save();
                    return json_encode(array('success' => true, 'info' => '设置成功'));
                }
            }
            return json_encode(array('success' => true, 'info' => '文章不存在'));
        })->conditions(array('aid' => '\d+'));

        $app->post('/admin/content/:aid/top/create', function($aid) use ($app) {
            $article = Article::find($aid);
            if ($article) {
                if (!$article->is_top) {
                    $article->setTop();
                    $article->save();
                    return json_encode(array('success' => true, 'info' => '设置成功'));
                }
            }
            return json_encode(array('success' => true, 'info' => '文章不存在'));
        })->conditions(array('aid' => '\d+'));

        $app->post('/admin/content/:aid/top/delete', function($aid) use ($app) {
            $article = Article::find($aid);
            if ($article) {
                if ($article->is_top) {
                    $article->setNotTop();
                    $article->save();
                    return json_encode(array('success' => true, 'info' => '设置成功'));
                }
            }else {
                return json_encode(array('success' => true, 'info' => '文章不存在'));
            }
        })->conditions(array('aid' => '\d+'));

        $app->post('/admin/content/:aid/delete', function($aid) use ($app) {
            $article = Article::find($aid);
            if ($article) {
                if (!$article->is_deleted) {
                    $article->delete();
                    $article->save();
                    return json_encode(array('success' => true, 'info' => '删除成功'));
                }
            }else {
                return json_encode(array('success' => true, 'info' => '文章不存在'));
            }
        })->conditions(array('aid' => '\d+'));

    }
);
