<?php

namespace Controller\Admin;

use \Model\User;
use \Model\Permission;

class ProfileEdit extends AdminBase {

    static public $url = '/admin/profile/edit';

    static public function get () {
        return self::render('admin/profile_edit.html', get_defined_vars());
    }

    static public function post () {
        $user = \GlobalEnv::get('user');
        $username = self::$request->post('username');
        $phone = self::$request->post('phone');
        $email = self::$request->post('mail');
        if ($username != $user->getUsername() && User::checkExist($username)) {
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
