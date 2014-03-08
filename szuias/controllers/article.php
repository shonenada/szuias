<?php

use \Captcha;

return array(
    "export" => function($app) {

        $app->get('/article/list', function () use ($app) {
            $category = '新闻动态';
            $sub_categories = array(
                array('name' => '学院公告', 'current' => true),
                array('name' => '学生工作'),
                array('name' => '学术科研'));
            $app->render('list.html', get_defined_vars());
        });

        $app->get('/article/:id', function ($id) use($app) {
            $title = '第二届华南计算机学科发展研讨会在深圳大学';
            $category = '新闻动态';
            $content = "asdf";
            $sub_categories = array(
                array('name' => '学院公告'),
                array('name' => '学生工作'),
                array('name' => '学术科研'));
            return $app->render('article.html', get_defined_vars());
        })->conditions(array('id' => '\d+'));
    }
);
