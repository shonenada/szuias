<?php

namespace Controller\Admin;

use \Model\Menu;
use \Model\Permission;
use \Model\Category as CategoryModel;

class CategorySave extends \Controller\Base {

    static public $name = 'admin_category_save';
    static public $url = '/admin/category/save';

    static public function post () {
        Permission::auth_model(Permission::$models['category'][0]);
        $post = urldecode(self::$request->params('menus'));
        $info = json_decode($post, true);
        $currentUser = \GlobalEnv::get('user');

        foreach ($info as $one) {
            $menu = Menu::find($one['mid']);
            $menu->classify = $one['classify'];
            $menu->save();
            foreach($one['categories'] as $ca) {
                if (empty($ca['cid'])) {
                    $category = new CategoryModel();
                    $category->menu = $menu;
                    $category->creator = $currentUser;
                }
                else {
                    $category = CategoryModel::find($ca['id']);
                }
                $category->title = $ca['title'];
                $category->sort = $ca['sort'];
                $category->save();
            }
        }

        return json_encode(array('success' => true, 'info' => '保存成功'));
    }

}
