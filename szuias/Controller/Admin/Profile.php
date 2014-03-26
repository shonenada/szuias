<?php

namespace Controller\Admin;

use \Model\Permission;

class Profile extends AdminBase {

    static public $name = 'admin_profile';
    static public $url = '/admin/profile';

    static public function get () {
        return self::render('admin/profile.html', get_defined_vars());
    }

}
