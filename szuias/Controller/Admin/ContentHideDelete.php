<?php

namespace Controller\Admin;

use \Model\Article;
use \Model\Permission;


class ContentHideDelete extends \Controller\Base {

    static public $name = 'admin_content_hide_delete';
    static public $url = '/admin/content/:aid/hide/delete';
    static public $conditions = array('aid' => '\d+');

    static public function post ($aid) {
        Permission::auth_model(Permission::$models['content'][0]);
        $article = Article::find($aid);
        if ($article) {
            if ($article->is_hide) {
                $article->show();
                $article->save();
                return json_encode(array('success' => true, 'info' => '设置成功'));
            }
        }
        return json_encode(array('success' => true, 'info' => '文章不存在'));
    }

}
