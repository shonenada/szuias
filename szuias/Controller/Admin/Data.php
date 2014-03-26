<?php

namespace Controller\Admin;

use \Model\Permission;

class Data extends \Controller\Base {

    static public $name = 'admin_data';
    static public $url = '/admin/data';

    static public function get () {
        Permission::auth_model(Permission::$models['data'][0]);
        return self::redirect('/admin/data/backup');
    }

}
