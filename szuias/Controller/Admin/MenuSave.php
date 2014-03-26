<?php

namespace Controller\Admin;

use \Model\Menu as MenuModel;
use \Model\Permission;


class MenuSave extends AdminBase {

    static public $name = 'admin_menu_save';
    static public $url = '/admin/menu/save';

    static public function post () {
        // 从 客户端 获取 post 的信息，并进行解码
        $post = urldecode(self::$request->params('menus'));
        $menus = json_decode($post, true);

        foreach ($menus as $m) {
            // $m['id'] 为空的时候表示数据库中不存在，即等待创建的数据
            if (empty($m['id'])) {
                // 创建新对象，从数据中获取数据，并保存到数据库中
                $firstMenu = new MenuModel();
                $firstMenu->populate_from_array($m)->save();

                foreach ($m['subMenus'] as $sub) {
                    $subMenu = new MenuModel();
                    $subMenu->parent = $firstMenu;  // 设置父 menu
                    $subMenu->populate_from_array($sub)->save();
                }
            }
            // 数据库中已存在的数据，等待修改的数据
            else {
                $menu = MenuModel::find($m['id']);
                if ($menu == null){
                    return json_encode(array(
                        'success' => false,
                        'info' => '对象不存在'
                    ));
                }
                $menu->populate_from_array($m)->save();

                foreach ($m['subMenus'] as $sub) {
                    if (empty($sub['id'])) {
                        $subMenu = new MenuModel();
                        $subMenu->parent = $menu;
                    }
                    else {
                        $subMenu = MenuModel::find($sub['id']);
                    }
                    $subMenu->populate_from_array($sub)->save();
                }
            }
        }
        return json_encode(array('success' => true, 'info' => '保存成功!'));
    }

}
