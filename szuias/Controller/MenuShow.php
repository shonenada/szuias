<?php

namespace Controller;

use \Model\Menu;
use \Model\Article;

class MenuShow extends Base {

    static public $url = '/menu/:mid/show';
    static public $conditions = array('mid' => '\d+');

    static public function get($mid) {
        $a = Article::findOneBy(array('menu' => Menu::find($mid)));
        if ($a)
            return self::redirect(self::urlFor('article_get', array('id' => $a->id)));
        else
            return self::redirect('/');
    }

}
