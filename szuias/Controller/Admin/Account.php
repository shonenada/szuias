<?php

namespace Controller\Admin;

use \Model\Menu;
use \Model\User;
use \Model\Permission;


class Account extends AdminBase {

    static public $url = '/admin/account';

    static public function get () {
        Permission::authModel(Permission::$models['account'][0]);
        $menus = Menu::getTopMenus();
        $accounts = User::listNotAdmin();
        $model_list = Permission::$models;
        return self::render('admin/account.html', get_defined_vars());
    }

}
