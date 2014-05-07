<?php

namespace Controller\Admin;

use \Model\Menu as MenuModel;

class MenuView extends AdminBase {

    static public $url = '/admin/menu/:mid';

    static public function get ($mid) {
        $menu = MenuModel::find($mid);
        if ($menu == null) {
            return self::redirect('/admin/menu');
        }
        $menus = MenuModel::get_top_menus($all=true);
        return self::render("admin/menu_view.html", get_defined_vars());
    }

}
