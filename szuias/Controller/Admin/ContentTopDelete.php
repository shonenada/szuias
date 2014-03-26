<?php

namespace Controller\Admin;

use \Model\Article;
use \Model\Permission;


class ContentTopDelete extends AdminBase {

    static public $name = 'admin_content_top_delete';
    static public $url = '/admin/content/:aid/top/delete';
    static public $conditions = array('aid' => '\d+');

    static public function post ($aid) {
        $article = Article::find($aid);
        if ($article) {
            if ($article->is_top) {
                $article->setNotTop();
                $article->save();
                return json_encode(array('success' => true, 'info' => '设置成功'));
            }
        }else {
            return json_encode(array('success' => true, 'info' => '文章不存在'));
        }
    }

}
