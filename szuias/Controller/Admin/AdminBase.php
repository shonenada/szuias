<?php

namespace Controller\Admin;

use \Model\Permission;

class AdminBase extends \Controller\Base {

    static public function before() {
        Permission::authModel(Permission::$models['account'][0]);
    }

}