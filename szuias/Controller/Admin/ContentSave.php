<?php

namespace Controller\Admin;

use \Model\Article;
use \Model\Permission;


class ContentSave extends AdminBase {

    static public $url = '/admin/content/save';

    static public function post ($mid=null) {
        $post = urldecode($_POST['sorts']);
        $sorts = json_decode($post, true);
        foreach ($sorts as $item){
            //保存排序
            if (!empty($item['aid'])){
                $article = Article::find($item['aid']);
                $article->sort = $item['sort'];
                $article->save();
            }
        }
        return json_encode(array("success" => true, "info" => "保存成功！"));
    }

}
