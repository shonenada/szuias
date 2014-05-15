<?php

namespace Controller\Admin;

use \Model\Menu;
use \Model\Permission;

class Category extends AdminBase {

    static public $url = '/admin/category';

    static public function get () {
        $menus = Menu::getByTypes(array(2));
        return self::render('admin/category.html', get_defined_vars());
    }

}
