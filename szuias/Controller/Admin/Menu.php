<?php

namespace Controller\Admin;

use \Model\Menu as MenuModel;

class Menu extends AdminBase {

    static public $url = '/admin/menu';

    static public function get () {
        $menus = MenuModel::get_top_menus($all=true);
        return self::render("admin/menu.html", get_defined_vars());
    }

}
