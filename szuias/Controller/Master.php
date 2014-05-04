<?php

namespace Controller;

use \Utils;
use \Model\File;
use \Model\Article;

class Master extends Base {

    static public $url = '/';

    static public function get() {
        $home = true;
        $news = Article::get_list_by_top_menu(10, 7, array(array('is_top', 'DESC'), array('sort', 'ASC'), array('created', 'DESC')));
        $undergraduate = Article::get_list_by_top_menu(6, 10, array(array('sort', 'ASC')));
        $graduate = Article::get_list_by_top_menu(6, 11, array(array('sort', 'ASC')));
        $research = Article::get_list_by_top_menu(6, 12, array(array('sort', 'ASC')));
        $working = Article::get_list_by_top_menu(7, 17, array(array('sort', 'ASC')));
        $slider = File::get_top();
        $teacher = Article::get_random_by_mids(array(14, 15));
        $teacher_img = Utils::get_teacher_img($teacher->content);
        $teacher_intro = Utils::remove_html($teacher->content);
        unset($teacher);
        return self::render("index.html", get_defined_vars());
    }

}
