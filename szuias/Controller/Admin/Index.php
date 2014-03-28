<?php

namespace Controller\Admin;

class Index extends AdminBase {

    static public $url = '/admin';

    static public function get () {
        $user = \GlobalEnv::get('user');
        if ($user == NULL) {
            return self::redirect(self::urlFor('admin_signin_get'));
        }
        else {
            return self::redirect('/admin/content');
        }
    }

}
