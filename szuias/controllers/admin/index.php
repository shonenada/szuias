<?php

/** 
 * 管理员登录页面控制器
 * @author shonenada
 *
 */

use \Model\User;

return array(
    "export" => function($app) {

        // 渲染管理员首页
        $app->get("/admin", function() use($app) {
            $user = $app->environment['user'];
            if ($user == NULL) {
                return $app->redirect('/admin/signin');
            }

            $app->render("admin/index.html", get_defined_vars());
        });

    }
);
