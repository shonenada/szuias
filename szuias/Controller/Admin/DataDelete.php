<?php

namespace Controller\Admin;

use \Model\Scheme;
use \Model\Permission;

class DataDelete extends \Controller\Base {

    static public $name = 'admin_data_delete';
    static public $url = '/admin/data/delete';

    static public function post () {
        Permission::auth_model(Permission::$models['data'][0]);
        $prefix = self::$request->post('ret');
        if (Scheme::deleteSqlFile($prefix)) {
            return json_encode(array('success' => true, 'error' => '删除成功'));
        }else {
            return json_encode(array('success' => false, 'error' => '删除失败'));
        }
    }

}
