<?php

namespace Controller\Admin;

use \Model\ClassApplication;

class ApplicationDelete extends AdminBase {

    static public $url = '/admin/apply/:wid/delete';
    static public $conditions = array('wid' => '\d+');

    static public function post ($wid) {
        $info = ClassApplication::find($wid);
        if ($info == null) {
            return json_encode(array('success' => false, 'info' => '对象不存在'));
        }
        $info->delete();
        return json_encode(array('success' => true, 'info' => '删除成功'));
    }

}
