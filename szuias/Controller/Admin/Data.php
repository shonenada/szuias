<?php

namespace Controller\Admin;

use \Model\Permission;

class Data extends AdminBase {

    static public $name = 'admin_data';
    static public $url = '/admin/data';

    static public function get () {
        return self::redirect('/admin/data/backup');
    }

}
