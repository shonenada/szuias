<?php

namespace Controller;

class Article extends Base {

    static public $name = 'article';
    static public $url = '/article/:id';
    static public $conditions = array('id' => '\d+');

    static public function get($id) {
        $article = \Model\Article::find($id);
        if ($article == null || $article->is_hide || $article->is_deleted) {
            return self::redirect('/');
        }
        if ($article->menu->is_parent()) {
            $top_menu = $article->menu;
        } else {
            $top_menu = $article->menu->parent;
        }
        $article->view_count += 1;
        $article->save();
        return self::render('article.html', get_defined_vars());
    }

}
