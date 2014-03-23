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
            $accounts = User::list_no_admin();
            return $app->render('admin/account.html', get_defined_vars());
        });

        $app->post('/admin/account/create', function() use($app) {
            $username = $app->request->post('username');
            $nickname = $app->request->post('nickname');
            $passwd = $app->request->post('password');
            $repasswd = $app->request->post('repassword');
            if ($passwd != $repasswd) {
                return json_encode(array('success' => false, 'info' => '密码与重复密码不匹配，请重新输入'));
            }
            if (User::check_exist($username)) {
                return json_encode(array('success' => false, 'info' => '用户名已存在，请重新输入'));
            }
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($passwd, $app->config('salt'));
            $user->setName($nickname);
            $user->save();
            return json_encode(array('success' => true, 'info' => '添加成功'));
        });

        $app->post('/admin/account/delete', function() use ($app) {
            $uid = $app->request->post('uid');
            $user = User::find($uid);
            if ($user->isAdmin()) {
                return json_encode(array('success' => false, 'info' => '无法删除管理员'));
            }
            $user->delete();
            return json_encode(array('success' => true, 'info' => '删除成功'));
        });

        $app->post('/admin/account/reset', function() use ($app) {
            $uid = $app->request->post('uid');
            $user = User::find($uid);
            if ($user->isAdmin()) {
                return json_encode(array('success' => false, 'info' => '无法修改管理员帐号'));
            }
            $user->setPassword('26532653', $app->config('salt'));
            $user->save();
            return json_encode(array('success' => true, 'info' => '26532653'));
        });

    }
);
