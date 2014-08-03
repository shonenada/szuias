<?php

namespace Controller\Admin;

use \Model\Slider;

class SettingSliderCreate extends AdminBase {

    static public $url = '/admin/setting/slider/create';

    static public function get () {
        return self::render('admin/setting_slider_create.html', get_defined_vars());
    }

    static public function post() {
        $post = urldecode(self::$request->params('sliders'));
        $sliders = json_decode($post, true);

        $slider = $sliders[0];

        $obj = new Slider();
        $obj->sort = $slider['sort'];
        $obj->title = $slider['title'];
        $obj->img_url = $slider['img_url'];
        $obj->target_url = $slider['target_url'];

        $obj->save();

        return json_encode(array("success" => true, "info" => "保存成功！"));

    }

}
