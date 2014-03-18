<?php

use \Captcha;
use \Model\Article;

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
            $article = Article::find($id);
            if ($article == null) {
                return $app->redirect('/');
            }
            if ($article->menu->is_parent()) {
                $top_menu = $article->menu;
            } else {
                $top_menu = $article->menu->parent;
            }
            return $app->render('article.html', get_defined_vars());
        })->conditions(array('id' => '\d+'));
    }
);
