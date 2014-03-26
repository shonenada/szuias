<?php

namespace Controller\Admin;

class Index extends \Controller\Base {

    static public $name = 'admin_index';
    static public $url = '/admin';

    static public function get () {
        $user = \GlobalEnv::get('user');
        if ($user == NULL) {
            return self::redirect('/admin/signin');
        }
        else {
            return self::redirect('/admin/content');
        }
    }

}
