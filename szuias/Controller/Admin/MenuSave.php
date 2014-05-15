<?php

namespace Controller\Admin;

use \Model\Menu as MenuModel;
use \Model\Permission;


class MenuSave extends AdminBase {

    static public $url = '/admin/menu/save';

    static public function post () {
        // 从 客户端 获取 post 的信息，并进行解码
        $post = urldecode(self::$request->params('menus'));
        $menus = json_decode($post, true);

        foreach ($menus as $m) {

            if (isset($m['id'])) {
                $menu = MenuModel::find($m['id']);
                if ($menu == null){
                    return json_encode(array(
                        'success' => false,
                        'info' => '对象不存在'
                    ));
                }
                $menu->populateFromArray($m)->save();
                $zh = $menu->translate('zh');
                $en = $menu->translate('en');
                $zh->title = $m['title'];
                $en->title = $m['title_eng'];
                $zh->save();
                $en->save();
            }
        }
        return json_encode(array('success' => true, 'info' => '保存成功!'));
    }

}
