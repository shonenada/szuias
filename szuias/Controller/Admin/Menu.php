<?php

namespace Controller\Admin;

use \Model\Menu as MenuModel;
use \Model\Permission;


class Menu extends \Controller\Base {

    static public $name = 'admin_menu';
    static public $url = '/admin/menu';

    static public function get () {
        Permission::auth_model(Permission::$models['menu'][0]);
        $menus = MenuModel::get_top_menus($all=true);
        return self::render("admin/menu.html", get_defined_vars());
    }

}
