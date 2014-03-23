<?php

/** 
 * 用户管理页面
 * @author shonenada
 *
 */

use Model\User;
use Model\Menu;
use Model\Permission;

return array(
    "export" => function($app) {

        $app->get("/admin/account", function() use($app) {
            $menus = Menu::get_top_menus();
            $accounts = User::list_no_admin();
            $model_list = Permission::$models;
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

        $app->post('/admin/account/permission', function() use($app) {
            $uid = $app->request->post('uid');
            $user = User::find($uid);
            if (!$user) {
                return json_encode(array('success' => false, 'info' => '用户不存在'));
            }
            $ps = $user->permissions;
            $type_name = Permission::$type_name;
            $permissions = array(
                'menu' => array(),
                'model' => array()
            );
            foreach ($ps as $obj) {
                $permissions[$type_name[$obj->type]][] = $obj->mid;
            }
            return json_encode($permissions);
        });

        $app->post('/admin/account/permission/save', function() use($app) {
            $uid = $app->request->post('uid');
            $user = User::find($uid);

            if (!$user)
                return json_encode(array('success' => false, 'info' => '用户不存在'));

            $mids = explode(",", $app->request->post('mids'));
            $models = explode(",", $app->request->post('models'));
            $types = Permission::$types;

            $pids = $user->permission_ids();

            $mids_to_remove = array_diff($pids['menu'], $mids);
            $models_to_remove = array_diff($pids['model'], $models);

            foreach ($mids_to_remove as $mid) {
                $p = Permission::findOneBy(array('mid' => $mid, 'uid' => $uid, 'type' => $types['menu']));
                if ($p) {
                    $p->remove();
                }
            }
            foreach ($models_to_remove as $mid) {
                $p = Permission::findOneBy(array('mid' => $mid, 'uid' => $uid, 'type' => $types['model']));
                if ($p) {
                    $p->remove();
                }
            }

            $mids_to_add = array_diff($mids, $pids['menu']);
            $models_to_add = array_diff($models, $pids['model']);

            foreach ($mids_to_add as $mid) {
                $p = new Permission();
                $p->type = $types['menu'];
                $p->user = $user;
                $p->mid = $mid;
                $p->save();
            }
            foreach ($models_to_add as $mid) {
                $p = new Permission();
                $p->type = $types['model'];
                $p->user = $user;
                $p->mid = $mid;
                $p->save();
            }

            return json_encode(array('success' => true, 'info' => '修改成功'));
        });

    }
);
