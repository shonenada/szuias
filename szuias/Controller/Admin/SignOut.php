<?php

namespace Controller\Admin;

class SignOut extends \Controller\Base {

    static public $url = '/admin/signout';

    static public function get () {
        self::$app->deleteCookie('user_id');
        self::$app->deleteCookie('token');
        return self::redirect(self::urlFor('admin_sign_in_get'));
    }
}
