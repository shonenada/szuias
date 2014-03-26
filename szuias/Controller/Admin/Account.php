<?php

namespace Controller\Admin;

use \Model\Menu;
use \Model\User;
use \Model\Permission;


class Account extends AdminBase {

    static public $url = '/admin/account';

    static public function get () {
        Permission::auth_model(Permission::$models['account'][0]);
        $menus = Menu::get_top_menus();
        $accounts = User::list_no_admin();
        $model_list = Permission::$models;
        return self::render('admin/account.html', get_defined_vars());
    }

}
