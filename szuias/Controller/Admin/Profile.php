<?php

namespace Controller\Admin;

use \Model\Permission;

class Profile extends \Controller\Base {

    static public $name = 'admin_profile';
    static public $url = '/admin/profile';

    static public function get () {
        Permission::auth_model(Permission::$models['profile'][0]);
        return self::render('admin/profile.html', get_defined_vars());
    }

}
