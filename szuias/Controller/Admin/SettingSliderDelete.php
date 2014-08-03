<?php

namespace Controller\Admin;

use \Model\Slider;

class SettingSliderDelete extends AdminBase {

    static public $url = '/admin/setting/slider/:sid/delete';
    static public $conditions = array('sid' => '\d+');

    static public function get ($sid) {
        $key_from_url = self::$request->get('token');
        $key = self::$app->getcookie('key');
        if ($key_from_url == $key) {
            $obj = Slider::find($sid);
            if ($obj) {
                $obj->remove();
            }
        }
        return self::redirect('/admin/setting');
    }

}
