<?php

namespace Controller\Admin;

use \Model\Slider;

class SettingSliderSave extends AdminBase {

    static public $url = '/admin/setting/slider/save';

    static public function post () {
        $post = urldecode(self::$request->params('sliders'));
        $sliders = json_decode($post, true);

        foreach ($sliders as $one) {
            $obj = Slider::find($one['id']);
            if ($obj == null) {
                continue;
            }
            $obj->sort = $one['sort'];
            $obj->title = $one['title'];
            $obj->img_url = $one['img_url'];
            $obj->target_url = $one['target_url'];
            $obj->save();
        }

        return json_encode(array("success" => true, "info" => "保存成功！"));
   
    }

}
