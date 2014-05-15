<?php

namespace Controller\Admin;

use \Model\Menu;
use \Model\User;
use \Model\Article;
use \Model\Category as CategoryModel;
use \Model\Permission;


class ContentSearch extends AdminBase {

    static public $url = '/admin/content/menu/:mid/search';
    static public $conditions = array('mid' => '\d+');

    static public function post ($mid) {
        $page = self::$request->get('page');
        $pagesize = self::$app->config('pagesize');
        $focus_menu = Menu::find($mid);
        if ($focus_menu->isParent()) {
            $top_menu = $focus_menu;
        }
        else {
            $top_menu = $focus_menu->parent;
        }
        $title = self::$request->post('title');
        $cid = self::$request->post('cid');
        $author_id = self::$request->post('creator');
        $rtime = self::$request->post('time');
        if ($rtime > 0){
            $post_from = new \DateTime();
            $post_from->setTimestamp(time() - (int)($rtime) * 24 * 3600);
        }else {
            $post_from = null;
        }
        $artilce_pager = Article::search($mid, $title, $cid, $author_id, $post_from);
        $total = $artilce_pager->count();
        $now = new \DateTime('now', new \DateTimezone('Asia/Shanghai'));
        $admin_menus = Menu::listAdminMenus();
        $categories = CategoryModel::all();
        $admin_list = User::all();
        $pager = array('current' => $page, 'nums' => ceil($total / $pagesize));
        $search = array('title' => $title, 'cid' => $cid, 'author_id' => $author_id, 'time' => $rtime);
        return self::render("admin/content.html", get_defined_vars());
    }

}
