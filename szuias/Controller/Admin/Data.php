<?php

namespace Controller\Admin;

use \Model\Permission;

class Data extends AdminBase {

    static public $url = '/admin/data';

    static public function get () {
        return self::redirect(self::urlFor('admin_data_backup_get'));
    }

}
