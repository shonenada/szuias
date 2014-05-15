<?php

namespace Controller\Admin;

use \Model\Menu as MenuModel;

class MenuView extends AdminBase {

    static public $url = '/admin/menu/:mid';
    static public $conditions = array('mid' => '\d+');

    static public function get ($mid) {
        $menu = MenuModel::find($mid);
        if ($menu == null || $menu->type != 0) {
            return self::redirect('/admin/menu');
        }
        $all_menus = MenuModel::getTopMenus($all=true);
        $node_menus = array_filter($all_menus, function ($one) {
            return $one->type == 0;
        });
        $menus = $menu->getSubMenus();
        return self::render("admin/menu.html", get_defined_vars());
    }

}
