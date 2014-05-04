<?php

namespace Controller;

use \Utils;
use \Model\Article;

class Search extends Base {

    static public function post() {
        $keyword = self::$request->post('keyword');
        $articles = Article::search_all_articles($keyword);
        return self::render('search.html', get_defined_vars());
    }

}
