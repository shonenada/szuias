<?php

namespace Controller\Admin;

use \Model\Menu as MenuModel;
use \Model\MenuContent;

class MenuCreate extends AdminBase {

    static public $url = '/admin/menu/create';

    static public function get () {
        $menus = MenuModel::getTopMenus($all=true);
        $node_menus = array_filter($menus, function ($one) {
            return $one->type == 0;
        });
        return self::render('admin/menu_create.html', get_defined_vars());
    }

    static public function post() {
        $post = urldecode(self::$request->params('menus'));
        $menu = json_decode($post, true);

        $success = true;
        $info = '';

        if (!is_numeric($menu['sort'])) {
            $success = false;
            $info = '排序只能是数字';
        }

        if (empty($menu['title'])) {
            $success = false;
            $info = '标题不能为空';
        }

        if (!isset($menu['type'])) {
            $success = false;
            $info = '类型不能为空';
        }

        if ($menu['type'] == 3 && empty($menu['outside_url'])) {
            $success = false;
            $info = '外部链接不能为空';
        }

        if (!isset($menu['open_style'])) {
            $success = false;
            $info = '打开方式不能为空';
        }

        if (empty($menu['is_show'])) {
            $success = false;
            $info = '显示不能为空';
        }

        if ($success) {
            $new_menu = new MenuModel();
            $new_menu->populateFromArray($menu)->save();

            $content = new MenuContent();
            $content->target = $new_menu;
            $content->lang = \GlobalEnv::get('translation.default');
            $content->title = $menu['title'];
            $content->save();

            if (isset($menu['title_eng'])) {
                $content_eng = new MenuContent();
                $content_eng->target = $new_menu;
                $content_eng->lang = \Model\Lang::getByCode('en');
                $content_eng->title = $menu['title_eng'];
                $content_eng->save();
            }
        }

        return json_encode(array(
            'success' => $success,
            'info' => $info,
        ));
    }

}
