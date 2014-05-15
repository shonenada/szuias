<?php

namespace Controller\Admin;

use \Model\Menu;
use \Model\User;
use \Model\Article;
use \Model\Category as CategoryModel;
use \Model\Permission;


class ContentView extends AdminBase {

    static public $url = '/admin/content/menu/:mid';
    static public $conditions = array('mid' => '\d+');

    static public function get ($mid) {
        $page = self::$request->get('page');
        $pagesize = self::$app->config('pagesize');
        if (empty($page)) {
            $page = 1;
        }

        $menu = Menu::find($mid);
        if ($menu == null) {
            return self::redirect('/admin/content');
        }

        if (in_array($menu->type, array(1, 2))) {
            $focus_menu = $menu;
            if (!$menu->isParent()) {
                $top_menu = $menu->parent;
            }else {
                $top_menu = $menu;
            }
        }else {
            $focus_menu = $top_menu = Menu::find($mid);
        }

        $artilce_pager = Article::paginateWithMid($page, $pagesize, $focus_menu->id, 'sort', true);
        $total = $artilce_pager->count();
        $now = new \DateTime('now', new \DateTimezone('Asia/Shanghai'));
        $menus = array($top_menu);
        $categories = CategoryModel::all();
        $admin_list = User::all();
        $pager = array('current' => $page, 'nums' => ceil($total / $pagesize));
        if ($focus_menu->type == 1) {
            // 单页
            $article = $focus_menu->articles->first();
            return self::render("admin/single_page.html", get_defined_vars());
        }
        else {
            return self::render("admin/content.html", get_defined_vars());
        }
    }

}
