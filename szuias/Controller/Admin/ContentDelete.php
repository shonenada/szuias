<?php

namespace Controller\Admin;

use \Model\Menu;
use \Model\User;
use \Model\Article;
use \Model\Permission;


class ContentDelete extends AdminBase {

    static public $url = '/admin/content/:aid/delete';
    static public $conditions = array('aid' => '\d+');

    static public function post ($aid) {
        $article = Article::find($aid);
        if ($article) {
            if (!$article->is_deleted) {
                $article->delete();
                $article->save();
                return json_encode(array('success' => true, 'info' => '删除成功'));
            }
        }else {
            return json_encode(array('success' => true, 'info' => '文章不存在'));
        }
    }

}
