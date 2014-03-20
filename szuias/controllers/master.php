<?php

use \Captcha;
use \Model\Setting;
use \Model\Article;

return array(
    "export" => function($app) {
        $app->get("/", function() use($app) {
            $home = true;
            $mid = 2;
            $slider = array(
                'nums' => Setting::get('index_slider', 'nums'),

            );
            $news = Article::get_list_by_top_menu(10, 6, 'sort', false);
            $app->render("index.html", get_defined_vars());
        });
        $app->get('/captcha', function () {
            $captcha = new Captcha(90, 30, 4);
            $captcha->showImg();
            $_SESSION['captchaCode'] = strtolower($captcha->getCaptcha());
        });
    }
);
