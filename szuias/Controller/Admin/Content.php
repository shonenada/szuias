<?php

namespace Controller\Admin;

use \Model\Menu;
use \Model\User;
use \Model\Article;
use \Model\Category as CategoryModel;
use \Model\Permission;


class Content extends AdminBase {

    static public $url = '/admin/content(/menu/:mid)';
    static public $conditions = array('mid' => '\d+');

    static public function get ($mid=null) {
        $page = self::$request->get('page');
        $pagesize = self::$app->config('pagesize');
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
                $focus_menu = $top_menu->getFirstSubMenu();
            }
        }
        else {
            $top_menu = $focus_menu->parent;
        }
        $artilce_pager = Article::paginate_with_mid($page, $pagesize, $focus_menu->id, 'sort', true);
        $total = $artilce_pager->count();
        $now = new \DateTime('now', new \DateTimezone('Asia/Shanghai'));
        $admin_menus = Menu::list_admin_menus();
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
