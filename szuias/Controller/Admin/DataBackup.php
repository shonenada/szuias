<?php

namespace Controller\Admin;

use \Model\Scheme;
use \Model\Permission;

class DataBackup extends \Controller\Base {

    static public $name = 'admin_data_backup';
    static public $url = '/admin/data/backup';

    static public function get () {
        Permission::auth_model(Permission::$models['data'][0]);
        $tables = Scheme::listTables();
        return self::render('admin/data_backup.html', get_defined_vars());
    }

    static public function post () {
        Permission::auth_model(Permission::$models['data'][0]);
        set_time_limit(0);
        $selected_tables = explode(",", self::$request->post('tabledb'));
        if (!Scheme::dumpDatabase($selected_tables)) {
        return json_encode(array('success' => false, 'error' => '发生未知原因，备份失败！'));
        }
        return json_encode(array('success' => true, 'error' => '备份成功！'));
    }

}
