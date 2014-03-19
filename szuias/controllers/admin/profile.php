<?php

/** 
 * 个人中心
 * @author shonenada
 *
 */

use Model\Menu;
use Model\Setting;

return array(
    "export" => function($app) {

        // 个人中心
        $app->get("/admin/profile", function() use($app) {
            return $app->render('admin/profile.html', get_defined_vars());
        });

        $app->get('/admin/profile/edit', function() use($app) {
            return $app->render('admin/profile_edit.html', get_defined_vars());
        });

        $app->post('/admin/profile/edit', function() use($app) {
            $user = $app->environment['user'];
            $username = $app->request->post('username');
            $phone = $app->request->post('phone');
            $email = $app->request->post('email');
            if (isset($username)) {
                $user->setUsername($username);
            }
            if (isset($phone) && is_numeric($phone)) {
                $user->setPhone($phone);
            }
            if (isset($email)) {
                $user->setEmail($email);
            }
            $user->save();
            $msg = '修改成功';
            return $app->render('admin/profile_edit.html', get_defined_vars());
        });

    }
);
