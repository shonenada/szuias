<?php

namespace Controller\Admin;

use \Model\Permission;
use \Model\Setting as SettingModel;

class SettingSave extends AdminBase {

    static public $url = '/admin/setting/save';

    static public function get () {
        $slider_nums = SettingModel::findByKey('index_slider', 'nums');
        $slider_fresh_time = SettingModel::findByKey('index_slider', 'fresh_time');
        $slider_source = SettingModel::findByKey('index_slider', 'source');

        if ($nums = self::$request->params('index_slider_nums')) {
            $slider_nums->value = $nums;
            $slider_nums->save();
        }
        
        if ($freshtime = self::$request->params('index_slider_fresh_time')) {
            $slider_fresh_time->value = $freshtime;
            $slider_fresh_time->save();
        }
        
        if ($source = self::$request->params('index_slider_source')) {
            $slider_source->value = $source;
            $slider_source->save();
        }

        return json_encode(array("success" => true, "info" => "保存成功！"));
   
    }

}
