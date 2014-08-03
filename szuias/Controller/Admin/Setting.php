<?php

namespace Controller\Admin;

use \Model\Menu as MenuModel;
use \Model\Setting as SettingModel;
use \Model\Slider;

class Setting extends AdminBase {

    static public $url = '/admin/setting';

    static public function get () {
        // $slider_nums = SettingModel::get('index_slider', 'nums');
        // $slider_fresh_time = SettingModel::get('index_slider', 'fresh_time');
        // $slider_source = SettingModel::get('index_slider', 'source');
        $sliders = Slider::all();
        $activities = SettingModel::get('activity', 'articles');
        $is_captcha = SettingModel::get('admin_signin', 'captcha');
        $menu_list = MenuModel::findBy(array('is_show' => '1', 'type' => '2'));
        $key = md5(rand(1, 100000));
        self::$app->setcookie('key', $key);
        return self::render('admin/setting.html', get_defined_vars());
    }

}
