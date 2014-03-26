<?php

namespace Controller\Admin;

use \Model\User;
use \Model\Permission;


class AccountDelete extends \Controller\Base {

    static public $name = 'admin_account_delete';
    static public $url = '/admin/account/delete';

    static public function post () {
        Permission::auth_model(Permission::$models['account'][0]);
        $uid = self::$request->post('uid');
        $user = User::find($uid);
        if ($user->isAdmin()) {
            return json_encode(array('success' => false, 'info' => '无法删除管理员'));
        }
        $user->delete();
        return json_encode(array('success' => true, 'info' => '删除成功'));
    }

}
