<?php

namespace Controller;

use \Utils;
use \Model\Menu;
use \Model\Article;

class MenuList extends Base {

    static public $name = 'menu_list';
    static public $url = '/menu/:mid/list';
    static public $conditions = array('mid' => '\d+');

    static public function get($mid) {
        $top_menu = Menu::find($mid);
        if (!$top_menu->is_parent()) {
            $top_menu = $top_menu->parent;
        }
        else if ($top_menu->has_sub()) {
            $sub = $top_menu->sub_menus->first();
            if ($sub->type == 1)
                return self::redirect("/menu/{$sub->id}/show");
            $mid = $sub->id;
        }
        $pagesize = self::$app->config('pagesize');
        $page = self::$request->get('page') ? self::$request->get('page') : 1;
        $articles = Article::get_list_by_menu_id($page, $pagesize, $mid, array(array('is_top', 'DESC'), array('sort', 'ASC'), array('created', 'DESC')));
        return self::render('list.html', get_defined_vars());
    }

}
