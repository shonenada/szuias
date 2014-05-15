<?php

namespace Controller;

class Article extends Base {

    static public $url = '/article/:id';
    static public $conditions = array('id' => '\d+');

    static public function get($id) {
        $article = \Model\Article::find($id);
        if ($article == null || $article->is_hide || $article->is_deleted) {
            return self::redirect('/');
        }
        if ($article->redirect_url != null) {
            return self::redirect($article->redirect_url);
        }
        if ($article->menu->isParent()) {
            $top_menu = $article->menu;
        } else {
            $top_menu = $article->menu;
            while(!$top_menu->isParent())
                $top_menu = $top_menu->parent;
        }
        $article->view_count += 1;
        $article->save();
        return self::render('article.html', get_defined_vars());
    }

}
