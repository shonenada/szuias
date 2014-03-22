<?php

use \Captcha;
use \Model\Article;
use \Model\File;

return array(
    "export" => function($app) {

        $app->get("/", function() use($app) {
            $home = true;
            $news = Article::get_list_by_top_menu(10, 6, array(array('sort', 'DESC')));
            $undergraduate = Article::get_list_by_top_menu(6, 10, array(array('sort', 'DESC')));
            $graduate = Article::get_list_by_top_menu(6, 11, array(array('sort', 'DESC')));
            $research = Article::get_list_by_top_menu(6, 12, array(array('sort', 'DESC')));
            $working = Article::get_list_by_top_menu(7, 17, array(array('sort', 'DESC')));
            $slider = File::get_top();
            $app->render("index.html", get_defined_vars());
        });

        $app->get('/captcha', function () {
            $captcha = new Captcha(90, 30, 4);
            $captcha->showImg();
            $_SESSION['captchaCode'] = strtolower($captcha->getCaptcha());
        });

    }
);
