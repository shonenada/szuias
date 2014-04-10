<?php

namespace Controller\Admin;

use \Model\Permission;

class ProfilePassword extends AdminBase {

    static public $url = '/admin/profile/password';

    static public function get () {
        return self::render('admin/profile_password.html', get_defined_vars());
    }

    static public function post () {
        $app = \GlobalEnv::get('app');
        $user = \GlobalEnv::get('user');
        $old = self::$request->post('oldpassword');
        if (!$user->checkPassword($old, $app->config('salt'))) {
            $msg = '旧密码错误，请重新输入';
        return self::render('admin/profile_password.html', get_defined_vars());
        }
        $new = self::$request->post('newpassword');
        $confirm = self::$request->post('confirmpassword');
        if ($new != $confirm) {
            $msg = '确认密码不匹配，请重新输入';
            return self::render('admin/profile_password.html', get_defined_vars());
        }
        $user->setPassword($confirm, $app->config('salt'));
        $user->save();
        $msg = '修改成功';
        return self::render('admin/profile_password.html', get_defined_vars());
    }

}
