<?php

namespace Controller\Admin;

use \Model\Scheme;
use \Model\Permission;

class DataRecover extends AdminBase {

    static public $url = '/admin/data/recover';

    static public function get () {
        $data = Scheme::listBackupFiles();
        return self::render('admin/data_recover.html', get_defined_vars());
    }

    static public function post () {
        $prefix = self::$request->post('ret');
        if (Scheme::importSqlFile($prefix)) {
            return json_encode(array('success' => true, 'error' => '恢复成功' ));
        }
        else {
            return json_encode(array('success' => false, 'error' => '发生未知原因，恢复失败！' ));
        }
    }

}
