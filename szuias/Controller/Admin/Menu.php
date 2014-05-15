<?php

namespace Controller\Admin;

use \Model\Menu as MenuModel;

class Menu extends AdminBase {

    static public $url = '/admin/menu';

    static public function get () {
        $menus = MenuModel::getTopMenus($all=true);
        $node_menus = array_filter($menus, function ($one) {
            return $one->type == 0;
        });
        return self::render("admin/menu.html", get_defined_vars());
    }

}
