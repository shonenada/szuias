<?php

namespace Controller\Admin;

use \Model\ClassApplication;

class ApplicationShow extends AdminBase {

    static public $url = '/admin/apply/:aid';
    static public $conditions = array('aid' => '\d+');

    static public function get ($aid) {
        $one = ClassApplication::find($aid);
        return self::render('admin/application_show.html', get_defined_vars());
    }

}
