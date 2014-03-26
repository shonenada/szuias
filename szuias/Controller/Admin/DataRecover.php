<?php

namespace Controller\Admin;

use \Model\Scheme;
use \Model\Permission;

class DataRecover extends \Controller\Base {

    static public $name = 'admin_data_backup';
    static public $url = '/admin/data/backup';

    static public function get () {
        Permission::auth_model(Permission::$models['data'][0]);
        $data = Scheme::listBackupFiles();
        return self::render('admin/data_recover.html', get_defined_vars());
    }

    static public function post () {
        Permission::auth_model(Permission::$models['data'][0]);
        $prefix = self::$request->post('ret');
        if (Scheme::importSqlFile($prefix)) {
            return json_encode(array('success' => true, 'error' => '恢复成功' ));
        }
        else {
            return json_encode(array('success' => false, 'error' => '发生未知原因，恢复失败！' ));
        }
    }

}
