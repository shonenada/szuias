<?php

namespace Controller\Admin;

use \Model\Menu as MenuModel;
use \Model\Permission;


class MenuDelete extends AdminBase {

    static public $url = '/admin/menu/:mid/delete';
    static public $conditions = array('mid' => '\d+');

    static public function post ($mid) {
        // TODO: 联动权限表删除
        $menu = MenuModel::find($mid);
        if ($menu == null) {
            return json_encode(array('success' => false, 'info' => '对象不存在'));
        }
        foreach ($menu->articles as $a){
            $a->delete();
        }
        $menu->delete();
        return json_encode(array('success' => true, 'info' => '删除成功!'));
    }

}
