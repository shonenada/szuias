<?php

/** 
 * 个人中心
 * @author shonenada
 *
 */

use Model\User;
use Model\Menu;
use Model\Permission;

return array(
    "export" => function($app) {

        // 个人中心
        $app->get("/admin/profile", function() use($app) {
            Permission::auth_model(Permission::$models['profile'][0]);
            return $app->render('admin/profile.html', get_defined_vars());
        });

        $app->get('/admin/profile/edit', function() use($app) {
            Permission::auth_model(Permission::$models['profile'][0]);
            return $app->render('admin/profile_edit.html', get_defined_vars());
        });

        $app->post('/admin/profile/edit', function() use($app) {
            Permission::auth_model(Permission::$models['profile'][0]);
            $user = \GlobalEnv::get('user');
            $username = $app->request->post('username');
            $phone = $app->request->post('phone');
            $email = $app->request->post('email');
            if ($username != $user->getUsername() && User::check_exist($username)) {
                $msg = '用户已存在，请重新输入';
                return $app->render('admin/profile_edit.html', get_defined_vars());
            }
            if (isset($phone) && !is_numeric($phone)) {
                $msg = '联系电话只能填数字';
                return $app->render('admin/profile_edit.html', get_defined_vars());
            }

            if (isset($username)) {
                $user->setUsername($username);
            }
            if (isset($phone)) {
                $user->setPhone($phone);
            }
            if (isset($email)) {
                $user->setEmail($email);
            }
            $user->save();
            $msg = '修改成功';
            return $app->render('admin/profile_edit.html', get_defined_vars());
        });

        $app->get('/admin/profile/password', function() use($app) {
            Permission::auth_model(Permission::$models['profile'][0]);
            return $app->render('admin/profile_password.html', get_defined_vars());
        });

        $app->post('/admin/profile/password', function() use($app) {
            $user = \GlobalEnv::get('user');
            $old = $app->request->post('oldpassword');
            if (!$user->checkPassword($old, $app->config('salt'))) {
                $msg = '旧密码错误，请重新输入';
                return $app->render('admin/profile_password.html', get_defined_vars());
            }
            $new = $app->request->post('newpassword');
            $confirm = $app->request->post('confirmpassword');
            if ($new != $confirm) {
                $msg = '确认密码不匹配，请重新输入';
                return $app->render('admin/profile_password.html', get_defined_vars());
            }
            $user->setPassword($confirm, $app->config('salt'));
            $user->save();
            $msg = '修改成功';
            return $app->render('admin/profile_password.html', get_defined_vars());
        });

    }
);
