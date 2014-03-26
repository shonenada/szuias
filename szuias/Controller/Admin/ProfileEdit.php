<?php

namespace Controller\Admin;

use \Model\User;
use \Model\Permission;

class ProfileEdit extends \Controller\Base {

    static public $name = 'admin_profile_edit';
    static public $url = '/admin/profile/edit';

    static public function get () {
        Permission::auth_model(Permission::$models['profile'][0]);
        return self::render('admin/profile_edit.html', get_defined_vars());
    }

    static public function post () {
        Permission::auth_model(Permission::$models['profile'][0]);
        $user = \GlobalEnv::get('user');
        $username = self::$request->post('username');
        $phone = self::$request->post('phone');
        $email = self::$request->post('email');
        if ($username != $user->getUsername() && User::check_exist($username)) {
            $msg = '用户已存在，请重新输入';
            return self::render('admin/profile_edit.html', get_defined_vars());
        }
        if (isset($phone) && !is_numeric($phone)) {
            $msg = '联系电话只能填数字';
            return self::render('admin/profile_edit.html', get_defined_vars());
        }

        if (isset($username)) {
            $user->setUsername($username);
        }
        if (isset($phone)) {
            $user->setPhone($phone);
        }
        if (isset($email)) {
            $user->setEmail($email);
        }
        $user->save();
        $msg = '修改成功';
        return self::render('admin/profile_edit.html', get_defined_vars());
    }

}
