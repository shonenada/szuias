<?php

namespace Controller\Admin;

use \Model\User;
use \Model\Permission;


class AccountPermission extends AdminBase {

    static public $url = '/admin/account/permission';

    static public function post () {
        $uid = self::$request->post('uid');
        $user = User::find($uid);
        if (!$user) {
            return json_encode(array('success' => false, 'info' => '用户不存在'));
        }
        $ps = $user->permissions;
        $type_name = Permission::$type_name;
        $permissions = array(
            'menu' => array(),
            'model' => array()
        );
        foreach ($ps as $obj) {
            $permissions[$type_name[$obj->type]][] = $obj->mid;
        }
        return json_encode($permissions);
    }

}
