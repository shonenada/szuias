<?php

namespace Controller\Admin;

use \Model\User;
use \Model\Permission;


class AccountCreate extends \Controller\Base {

    static public $name = 'admin_account_create';
    static public $url = '/admin/account/create';

    static public function post () {
        Permission::auth_model(Permission::$models['account'][0]);
        $username = self::$request->post('username');
        $nickname = self::$request->post('nickname');
        $passwd = self::$request->post('password');
        $repasswd = self::$request->post('repassword');
        if ($passwd != $repasswd) {
            return json_encode(array('success' => false, 'info' => '密码与重复密码不匹配，请重新输入'));
        }
        if (User::check_exist($username)) {
            return json_encode(array('success' => false, 'info' => '用户名已存在，请重新输入'));
        }
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($passwd, $app->config('salt'));
        $user->setName($nickname);
        $user->save();
        return json_encode(array('success' => true, 'info' => '添加成功'));
    }

}
