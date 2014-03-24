<?php

/** 
 * 数据管理
 * @author shonenada
 *
 */

use \Model\Scheme;
use \Model\Permission;

return array(
    "export" => function($app) {

        $app->get("/admin/data", function() use ($app) {
            Permission::auth_model(Permission::$models['data'][0]);
            return $app->redirect('/admin/data/backup');
        });

        $app->get('/admin/data/backup', function() use($app) {
            Permission::auth_model(Permission::$models['data'][0]);
            $tables = Scheme::listTables();
            return $app->render('admin/data_backup.html', get_defined_vars());
        });

        $app->post('/admin/data/backup', function() use($app) {
            Permission::auth_model(Permission::$models['data'][0]);
            set_time_limit(0);
            $selected_tables = explode(",", $app->request->post('tabledb'));
            if (!Scheme::dumpDatabase($selected_tables)) {
                return json_encode(array('success' => false, 'error' => '发生未知原因，备份失败！'));
            }
            return json_encode(array('success' => true, 'error' => '备份成功！'));
        });

        $app->get('/admin/data/recover', function() use($app) {
            Permission::auth_model(Permission::$models['data'][0]);
            $data = Scheme::listBackupFiles();
            return $app->render('admin/data_recover.html', get_defined_vars());
        });

        $app->post('/admin/data/recover', function() use($app) {
            Permission::auth_model(Permission::$models['data'][0]);
            $prefix = $app->request->post('ret');
            if (Scheme::importSqlFile($prefix)) {
                return json_encode(array('success' => true, 'error' => '恢复成功' ));
            }
            else {
                return json_encode(array('success' => false, 'error' => '发生未知原因，恢复失败！' ));
            }
        });

        $app->post('/admin/data/delete', function () use($app) {
            Permission::auth_model(Permission::$models['data'][0]);
            $prefix = $app->request->post('ret');
            if (Scheme::deleteSqlFile($prefix)) {
                return json_encode(array('success' => true, 'error' => '删除成功'));
            }else {
                return json_encode(array('success' => false, 'error' => '删除失败'));
            }
        });

    }
);
