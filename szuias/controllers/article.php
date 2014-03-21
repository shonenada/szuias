<?php

use \Captcha;
use \Model\Menu;
use \Model\Article;

return array(
    "export" => function($app) {

        $app->get('/menu/:mid/list', function ($mid) use ($app) {
            $menu = Menu::find($mid);
            if (!$menu->is_parent()) {
                $menu = $menu->parent;
            }
            $app->render('list.html', get_defined_vars());
        })->conditions(array('mid' => '\d+'));

        $app->get('/menu/:mid/show', function ($mid) use ($app) {
            $a = Article::findOneBy(array('menu' => Menu::find($mid)));
            if ($a)
                return $app->redirect('/article/' . $a->id);
            else
                return $app->redirect('/');
        });

        $app->get('/article/:id', function ($id) use ($app) {
            $article = Article::find($id);
            if ($article == null || $article->is_hide || $article->is_deleted) {
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
