<?php

/** 
 * 用户管理页面
 * @author shonenada
 *
 */

use Model\User;

return array(
    "export" => function($app) {

        $app->get("/admin/account", function() use($app) {
            $pagesize = $app->config('pagesize');
            $page = $app->request->get('page');
            if (empty($page)) {
                $page = 1;
            }
            $accounts = User::paginate($page, $pagesize);
            return $app->render('admin/account.html', get_defined_vars());
        });

    }
);
