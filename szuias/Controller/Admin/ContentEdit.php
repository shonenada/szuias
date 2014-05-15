<?php

namespace Controller\Admin;

use \Model\Menu;
use \Model\User;
use \Model\Article;
use \Model\ArticleContent;
use \Model\Category as CategoryModel;
use \Model\Permission;


class ContentEdit extends AdminBase {

    static public $url = '/admin/content/:aid/edit';
    static public $conditions = array('aid' => '\d+');

    static public function get ($aid) {
        $article = Article::find($aid);
        $focus_menu = $article->menu;
        if ($focus_menu->isParent()){
            $focus_sub_menu = $focus_menu->getFirstSubMenu();
        }
        else {
            $focus_sub_menu = $focus_menu;
            $focus_menu = $focus_menu->parent;
        }
        $admin_menus = Menu::listAdminMenus();
        $timestamp = $_SESSION['add_timestamp'] = time() * 10000 + rand(0, 9999);
        return self::render('admin/content_edit.html', get_defined_vars());
    }

    static public function post ($aid) {
        if ($_SESSION['add_timestamp'] != self::$request->post('timestamp')) {
            return self::redirect(self::urlFor('admin_content_edit_get', array('aid' => $aid)));
        }

        $article = Article::find($aid);
        if ($article == null) {
            return self::redirect(self::urlFor('admin_content_edit_get', array('aid' => $aid)));
        }
        $menu = $article->menu;
        $category = CategoryModel::find(self::$request->post('category_id'));
        $data = array(
            'menu' => $menu,
            'category' => $category,
            'editor' => \GlobalEnv::get('user'),
            'open_style' => self::$request->post('open_style'),
            'redirect_url' => self::$request->post('url'),
            'edit_time' => new \DateTime('now', new \DateTimezone('Asia/Shanghai')),
        );
        $article->populateFromArray($data)->save();
        $zh = $article->translate('zh');
        $en = $article->translate('en');
        $zh->title = self::$request->post('title');
        $zh->content = self::$request->post('content');
        $en->title = self::$request->post('title_eng');
        $en->content = self::$request->post('content_eng');
        $zh->save();
        $en->save();

        if (empty($_SESSION['upload_buffer'])){
            $upload_buffer = array();
        } else {
            $upload_buffer = $_SESSION['upload_buffer'];
        }
        foreach($upload_buffer as $f) {
            $f->article = $article;
            $f->save();
        }
        $_SESSION['upload_buffer'] = array();
        return self::redirect(self::urlFor('admin_content_get', array('mid' => $menu->id)));
    }

}
