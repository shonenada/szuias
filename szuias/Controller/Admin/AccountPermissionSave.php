<?php

namespace Controller\Admin;

use \Model\User;
use \Model\Permission;


class AccountPermissionSave extends \Controller\Base {

    static public $name = 'admin_account_permission_save';
    static public $url = '/admin/account/permission/save';

    static public function post () {
        Permission::auth_model(Permission::$models['account'][0]);
        $uid = self::$request->post('uid');
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
    }

}
