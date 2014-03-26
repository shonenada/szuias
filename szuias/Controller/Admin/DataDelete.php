<?php

namespace Controller\Admin;

use \Model\Scheme;
use \Model\Permission;

class DataDelete extends AdminBase {

    static public $url = '/admin/data/delete';

    static public function post () {
        $prefix = self::$request->post('ret');
        if (Scheme::deleteSqlFile($prefix)) {
            return json_encode(array('success' => true, 'error' => '删除成功'));
        }else {
            return json_encode(array('success' => false, 'error' => '删除失败'));
        }
    }

}
