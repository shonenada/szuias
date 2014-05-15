<?php

namespace Controller\Admin;

use \Model\Menu;
use \Model\User;
use \Model\Article;
use \Model\Category as CategoryModel;
use \Model\Permission;


class Content extends AdminBase {

    static public $url = '/admin/content';

    static public function get () {
        $page = self::$request->get('page');
        $pagesize = self::$app->config('pagesize');
        if (empty($page)) {
            $page = 1;
        }

        $focus_menu = Menu::getFirstMenu();

        if ($focus_menu->isParent()) {
            $top_menu = $focus_menu;
            if ($top_menu->hasSub()) {
                $focus_menu = $top_menu->getFirstSubMenu();
            }
        }
        else {
            $top_menu = $focus_menu->parent;
        }
        $artilce_pager = Article::paginateWithMid($page, $pagesize, $focus_menu->id, 'sort', true);
        $total = $artilce_pager->count();
        $now = new \DateTime('now', new \DateTimezone('Asia/Shanghai'));
        $menus = Menu::listAdminMenus();
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
