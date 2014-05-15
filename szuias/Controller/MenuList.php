<?php

namespace Controller;

use \Model\Menu;
use \Model\Article;

class MenuList extends Base {

    static public $url = '/menu/:mid/list';
    static public $conditions = array('mid' => '\d+');

    static public function get($mid) {
        $top_menu = Menu::find($mid);
        if (!$top_menu->isParent()) {
            while(!$top_menu->isParent())
                $top_menu = $top_menu->parent;
        }
        else if ($top_menu->hasSub()) {
            $sub = $top_menu->getFirstSubMenu();
            if ($sub->type == 1)
                return self::redirect(self::urlFor('menu_show_get', array('mid' => $sub->id)));
            $mid = $sub->id;
        }
        $menu = Menu::find($mid);
        $page = self::$request->get('page') ? self::$request->get('page') : 1;
        $pagesize = self::$app->config('pagesize');
        $articles = Article::getListByMenuId($page, $pagesize, $mid, array(array('is_top', 'DESC'), array('sort', 'ASC'), array('created', 'DESC')));
        return self::render('list.html', get_defined_vars());
    }

}
