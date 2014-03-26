<?php

namespace Controller\Admin;

use \Model\Menu as MenuModel;
use \Model\Permission;
use \Model\Setting as SettingModel;

class Setting extends \Controller\Base {

    static public $name = 'admin_setting';
    static public $url = '/admin/setting';

    static public function get () {
        Permission::auth_model(Permission::$models['setting'][0]);
        $slider_nums = SettingModel::get('index_slider', 'nums');
        $slider_fresh_time = SettingModel::get('index_slider', 'fresh_time');
        $slider_source = SettingModel::get('index_slider', 'source');
        $menu_list = MenuModel::findBy(array('is_show' => '0', 'type' => '2'));
        return self::render('admin/setting.html', get_defined_vars());
    }

}
