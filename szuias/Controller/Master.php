<?php

namespace Controller;

use \Utils;
use \Model\File;
use \Model\Article;
use \Model\Setting;


class Master extends Base {

    static public $url = '/';

    static public function get() {
        $home = true;
        $news = Article::getListByTopMenu(10, 7, array(array('is_top', 'DESC'), array('sort', 'ASC'), array('created', 'DESC')));
        // $news = Article::get_in(Setting::get('activity', 'articles'));
        $undergraduate = Article::getListByTopMenu(6, 10, array(array('sort', 'ASC')));
        $graduate = Article::getListByTopMenu(6, 11, array(array('sort', 'ASC')));
        $research = Article::getListByTopMenu(6, 12, array(array('sort', 'ASC')));
        $working = Article::getListByTopMenu(7, 17, array(array('sort', 'ASC')));
        $slider = File::getTop();
        $teacher = Article::getRandombyMids(array(14, 15));
        $teacher_img = Utils::getTeacherImg($teacher->getContent());
        $teacher_intro = Utils::removeHTML($teacher->getContent());
        unset($teacher);
        return self::render("index.html", get_defined_vars());
    }

}
