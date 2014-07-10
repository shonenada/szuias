<?php

namespace Controller\Admin;

use \Model\ClassApplication;

class Application extends AdminBase {

    static public $url = '/admin/apply';

    static public function get () {
        $applications = ClassApplication::all();
        return self::render('admin/application.html', get_defined_vars());
    }

}
