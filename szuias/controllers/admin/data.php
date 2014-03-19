<?php

/** 
 * 数据管理
 * @author shonenada
 *
 */

use Model\Scheme;

return array(
    "export" => function($app) {

        $app->get("/admin/data", function() use ($app) {
            return $app->redirect('/admin/data/backup');
        });

        $app->get('/admin/data/backup', function() use($app) {
            $tables = Scheme::listTables();
            return $app->render('admin/data_backup.html', get_defined_vars());
        });

        $app->post('/admin/data/backup', function() use($app) {
            set_time_limit(0);
            $selected_tables = explode(",", $app->request->post('tabledb'));
            if (!Scheme::dumpDatabase('../backup/', $selected_tables)) {
                return json_encode(array('success' => false, 'error' => '发生未知原因，备份失败！'));
            }
            return json_encode(array('success' => true, 'error' => '备份成功！'));
        });

    }
);
