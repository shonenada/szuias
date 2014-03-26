<?php

namespace Controller\Admin;

use \Model\Article;
use \Model\Permission;


class ContentTopCreate extends AdminBase {

    static public $url = '/admin/content/:aid/top/create';
    static public $conditions = array('aid' => '\d+');

    static public function post ($aid) {
        $article = Article::find($aid);
        if ($article) {
            if (!$article->is_top) {
                $article->setTop();
                $article->save();
                return json_encode(array('success' => true, 'info' => '设置成功'));
            }
        }
        return json_encode(array('success' => true, 'info' => '文章不存在'));
    }

}
