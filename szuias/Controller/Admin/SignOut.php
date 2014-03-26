<?php

namespace Controller\Admin;

class SignOut extends \Controller\Base {

    static public $name = 'admin_sign_out';
    static public $url = '/admin/signout';

    static public function get () {
        self::$app->deleteCookie('user_id');
        self::$app->deleteCookie('token');
        return self::redirect('/admin/signin');
    }
}
