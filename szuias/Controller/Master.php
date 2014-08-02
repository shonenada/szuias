<?php

namespace Controller;

use \Model\File;
use \Model\Article;
use \Model\Setting;
use \Util\Helper;
use \Util\HTMLHelper;


class Master extends Base {

    static public $url = '/';

    static public function get() {
        $home = true;
        // $news = Article::get_in(Setting::get('activity', 'articles'));
        $news = Article::getListByMenuId(1, 10, 7, array(array('is_top', 'DESC'), array('sort', 'DESC'), array('created', 'DESC')));
        $undergraduate = Article::getListByTopMenu(6, 10, array(array('sort', 'DESC')));
        $graduate = Article::getListByTopMenu(6, 11, array(array('sort', 'DESC')));
        $research = Article::getListByTopMenu(6, 12, array(array('sort', 'DESC')));
        $working = Article::getListByTopMenu(7, 17, array(array('sort', 'DESC')));
        $slider = File::getTop();
        return self::render("index.html", get_defined_vars());
    }

}
