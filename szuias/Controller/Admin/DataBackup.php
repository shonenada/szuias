<?php

namespace Controller\Admin;

use \Model\Scheme;
use \Model\Permission;

class DataBackup extends AdminBase {

    static public $url = '/admin/data/backup';

    static public function get () {
        $tables = Scheme::listTables();
        return self::render('admin/data_backup.html', get_defined_vars());
    }

    static public function post () {
        set_time_limit(0);
        $selected_tables = explode(",", self::$request->post('tabledb'));
        if (!Scheme::dumpDatabase($selected_tables)) {
        return json_encode(array('success' => false, 'error' => '发生未知原因，备份失败！'));
        }
        return json_encode(array('success' => true, 'error' => '备份成功！'));
    }

}
