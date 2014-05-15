<?php

namespace Controller\Admin;

use \Model\Menu;
use \Model\User;
use \Model\Article;
use \Model\Category as CategoryModel;
use \Model\Permission;


class ContentCreate extends AdminBase {

    static public $url = '/admin/content/menu/:menu_id/create';
    static public $conditions = array('menu_id' => '\d+');

    static public function get ($menu_id) {
        if (empty($menu_id)) {
            $focus_menu = Menu::getFirstMenu();
        }
        else {
            $focus_menu = Menu::find($menu_id);
        }
        if ($focus_menu->isParent()){
            $focus_sub_menu = $focus_menu->getFirstSubMenu();
        }
        else {
            $focus_sub_menu = $focus_menu;
            $focus_menu = $focus_menu->parent;
        }
        $post_menu = Menu::find($menu_id);
        $admin_menus = Menu::listAdminMenus();
        $timestamp = $_SESSION['add_timestamp'] = time() * 10000 + rand(0, 9999);
        return self::render('admin/content_create.html', get_defined_vars());
    }

    static public function post ($menu_id) {
        if ($_SESSION['add_timestamp'] != self::$request->post('timestamp')) {
            return self::render("admin/content_create.html", get_defined_vars());
        }
        $menu = Menu::find($menu_id);
        $user = \GlobalEnv::get('user');
        if ($menu->type == 1) {
            // 单页
            $a = Article::findOneBy(array('menu' => $menu));
            if ($a != null) {
                $info = '单页菜单只允许存在一篇文章，新增失败！';
                return self::render("admin/content_create.html", get_defined_vars());
            }
        }
        $article = new Article();
        $category = CategoryModel::find(self::$request->post('category_id'));
        $data = array(
            'menu' => $menu,
            'category' => $category,
            'author' => $user,
            'editor' => $user,
            'redicret_url' => self::$request->post('url'),
            'edit_time' => new \DateTime(self::$request->post('moditime')),
        );
        $open_style = self::$request->post('open_style');
        if (isset($open_style)) {
            $data['open_style'] = $open_style;
        }
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
        return self::redirect(self::urlFor('admin_content_get', array('mid' => $menu_id)));
    }

}
