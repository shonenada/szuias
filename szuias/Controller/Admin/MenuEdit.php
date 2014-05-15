<?php

namespace Controller\Admin;

use \Model\Menu as MenuModel;
use \Model\MenuContent;

class MenuEdit extends AdminBase {

    static public $url = '/admin/menu/:mid/edit';
    static public $conditions = array('mid' => '\d+');

    static public function get ($mid) {
        $menu = MenuModel::find($mid);
        if ($menu == null) {
            return self::redirect('/admin/menu');
        }
        $menus = MenuModel::getTopMenus($all=true);
        return self::render("admin/menu_edit.html", get_defined_vars());
    }

    static public function post ($mid) {
        $menu = MenuModel::find($mid);
        if ($menu == null) {
            return self::redirect('/admin/menu');
        }

        $post = urldecode(self::$request->params('menus'));
        $data = json_decode($post, true);

        $success = true;
        $info = '';

        if (!is_numeric($data['sort'])) {
            $success = false;
            $info = '排序只能是数字';
        }

        if (empty($data['title'])) {
            $success = false;
            $info = '标题不能为空';
        }

        if (!isset($data['type'])) {
            $success = false;
            $info = '类型不能为空';
        }

        if ($data['type'] == 3 && empty($data['outside_url'])) {
            $success = false;
            $info = '外部链接不能为空';
        }

        if (!isset($data['open_style'])) {
            $success = false;
            $info = '打开方式不能为空';
        }

        if (empty($data['is_show'])) {
            $success = false;
            $info = '显示不能为空';
        }

        if ($success) {
            $menu->populateFromArray($data)->save();
            $zh = $menu->translate('zh');
            $en = $menu->translate('en');
            $zh->title = $data['title'];
            $en->title = $data['title_eng'];
            $zh->save();
            $en->save();
        }

        return json_encode(array(
            'success' => $success,
            'info' => $info,
        ));
    }

}
