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
use \Model\Permission;

return array(
    "export" => function($app) {
        
        // 渲染内容管理界面
        $app->get("/admin/content(/menu/:mid)", function($mid=null) use($app) {
            Permission::auth_model(Permission::$models['content'][0]);
            $page = $app->request->get('page');
            $pagesize = $app->config('pagesize');
            if (empty($page)) {
                $page = 1;
            }

            if (empty($mid)) {
                $focus_menu = Menu::get_first_menu();
            }
            else {
                $focus_menu = Menu::find($mid);
            }

            if ($focus_menu->is_parent()) {
                $top_menu = $focus_menu;
                if ($top_menu->has_sub()) {
                    $focus_menu = $top_menu->sub_menus->first();
                }
            }
            else {
                $top_menu = $focus_menu->parent;
            }
            $artilce_pager = Article::paginate_with_mid($page, $pagesize, $focus_menu->id, 'sort', false);
            $total = $artilce_pager->count();
            $now = new \DateTime('now', new DateTimezone('Asia/Shanghai'));
            $admin_menus = Menu::list_admin_menus();
            $categories = Category::all();
            $admin_list = User::all();
            $pager = array('current' => $page, 'nums' => ceil($total / $pagesize));
            if ($focus_menu->type == 1) {
                // 单页
                $article = $focus_menu->articles->first();
                return $app->render("admin/single_page.html", get_defined_vars());
            }
            else {
                return $app->render("admin/content.html", get_defined_vars());
            }
        })->conditions(array('mid' => '\d+'));

        $app->get('/admin/content/menu/:id/create', function($menu_id) use ($app) {
            Permission::auth_model(Permission::$models['content'][0]);
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
            Permission::auth_model(Permission::$models['content'][0]);
            if ($_SESSION['add_timestamp'] != $app->request->post('timestamp')) {
                return $app->render("admin/content_create.html", get_defined_vars());
            }
            $menu = Menu::find($menu_id);
            $user = \GlobalEnv::get('user');
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
                'redicret_url' => $app->request->post('url'),
                'edit_time' => new \DateTime($app->request->post('moditime')),
            );
            $open_style = $app->request->post('open_style');
            if (isset($open_style)) {
                $data['open_style'] = $open_style;
            }
            $article->populate_from_array($data)->save();

            if (empty($_SESSION['upload_buffer'])){
                $upload_buffer = array();
            } else {
                $upload_buffer = $_SESSION['upload_buffer'];
            }
            foreach($upload_buffer as $f) {
                $f->article = $article;
                $f->save();
            }
            $_SESSION['upload_buffer'] = array();
            return $app->redirect('/admin/content/menu/' . $menu_id);
        })->conditions(array('id' => '\d+'));

        $app->get('/admin/content/:aid/edit', function($aid) use ($app) {
            Permission::auth_model(Permission::$models['content'][0]);
            $article = Article::find($aid);
            $focus_menu = $article->menu;
            if ($focus_menu->is_parent()){
                $focus_sub_menu = $focus_menu->sub_menus->first();
            }
            else {
                $focus_sub_menu = $focus_menu;
                $focus_menu = $focus_menu->parent;
            }
            $admin_menus = Menu::list_admin_menus();
            $timestamp = $_SESSION['add_timestamp'] = time() * 10000 + rand(0, 9999);
            return $app->render('admin/content_edit.html', get_defined_vars());
        })->conditions(array('aid' => '\d+'));

        $app->post('/admin/content/:aid/edit', function($aid) use ($app) {
            Permission::auth_model(Permission::$models['content'][0]);
            if ($_SESSION['add_timestamp'] != $app->request->post('timestamp')) {
                return $app->redirect("/admin/content/{$aid}/edit");
            }

            $article = Article::find($aid);
            if ($article == null) {
                return $app->redirect('/admin/content/{$aid}/edit');
            }
            $menu = $article->menu;
            $category = Category::find($app->request->post('category_id'));
            $data = array(
                'title' => $app->request->post('title'),
                'content' => $app->request->post('content'),
                'menu' => $menu,
                'category' => $category,
                'editor' => \GlobalEnv::get('user'),
                'open_style' => $app->request->post('open_style'),
                'redirect_url' => $app->request->post('url'),
                'edit_time' => new \DateTime('now', new DateTimezone('Asia/Shanghai')),
            );
            $article->populate_from_array($data)->save();

            if (empty($_SESSION['upload_buffer'])){
                $upload_buffer = array();
            } else {
                $upload_buffer = $_SESSION['upload_buffer'];
            }
            foreach($upload_buffer as $f) {
                $f->article = $article;
                $f->save();
            }
            $_SESSION['upload_buffer'] = array();
            return $app->redirect('/admin/content/menu/' . $menu->id);
        })->conditions(array('id' => '\d+'));

        $app->post('/admin/content/:aid/delete', function($aid) use ($app) {
            Permission::auth_model(Permission::$models['content'][0]);
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

        $app->post('/admin/content/:aid/hide/create', function($aid) use ($app) {
            Permission::auth_model(Permission::$models['content'][0]);
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
            Permission::auth_model(Permission::$models['content'][0]);
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
            Permission::auth_model(Permission::$models['content'][0]);
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
            Permission::auth_model(Permission::$models['content'][0]);
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

        $app->post('/admin/content/menu/:mid/search', function ($mid) use ($app) {
            Permission::auth_model(Permission::$models['content'][0]);
            $page = $app->request->get('page');
            $pagesize = $app->config('pagesize');
            $focus_menu = Menu::find($mid);
            if ($focus_menu->is_parent()) {
                $top_menu = $focus_menu;
            }
            else {
                $top_menu = $focus_menu->parent;
            }
            $title = $app->request->post('title');
            $cid = $app->request->post('cid');
            $author_id = $app->request->post('creator');
            $rtime = $app->request->post('time');
            if ($rtime > 0){
                $post_from = new \DateTime();
                $post_from->setTimestamp(time() - (int)($rtime) * 24 * 3600);
            }else {
                $post_from = null;
            }
            $artilce_pager = Article::search($mid, $title, $cid, $author_id, $post_from);
            $total = $artilce_pager->count();
            $now = new \DateTime('now', new DateTimezone('Asia/Shanghai'));
            $admin_menus = Menu::list_admin_menus();
            $categories = Category::all();
            $admin_list = User::all();
            $pager = array('current' => $page, 'nums' => ceil($total / $pagesize));
            $search = array('title' => $title, 'cid' => $cid, 'author_id' => $author_id, 'time' => $rtime);
            return $app->render("admin/content.html", get_defined_vars());
        })->conditions(array('mid' => '\d+'));

    }
);
