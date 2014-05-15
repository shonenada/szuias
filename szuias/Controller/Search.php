<?php

namespace Controller;

use \Model\Article;

class Search extends Base {

    static public function post() {
        $keyword = self::$request->post('keyword');
        $articles = Article::searchArticles($keyword);
        return self::render('search.html', get_defined_vars());
    }

}
