<?php

namespace Controller\Admin;

use \Model\Menu;
use \Model\Permission;

class Category extends \Controller\Base {

    static public $name = 'admin_category';
    static public $url = '/admin/category';

    static public function get () {
        Permission::auth_model(Permission::$models['category'][0]);
        $menus = Menu::get_by_types(array(2));
        return self::render('admin/category.html', get_defined_vars());
    }

}
