<?php

namespace Controller\Admin;

use \Model\Permission;
use \Model\Setting as SettingModel;

class SettingSave extends AdminBase {

    static public $url = '/admin/setting/save';

    static public function post () {
        $slider_nums = SettingModel::findByKey('index_slider', 'nums');
        $slider_fresh_time = SettingModel::findByKey('index_slider', 'fresh_time');
        $slider_source = SettingModel::findByKey('index_slider', 'source');
        $activites = SettingModel::findByKey('activity', 'articles');
        $is_captcha = SettingModel::findByKey('admin_signin', 'captcha');

        $nums = self::$request->params('index_slider_nums');
        $freshtime = self::$request->params('index_slider_fresh_time');
        $source = self::$request->params('index_slider_source');
        $articles = self::$request->params('activity_articles');
        $captcha = self::$request->params('is_captcha');

        if (isset($nums)) {
            $slider_nums->value = $nums;
            $slider_nums->save();
        }

        if (isset($freshtime)) {
            $slider_fresh_time->value = $freshtime;
            $slider_fresh_time->save();
        }

        if (isset($source)) {
            $slider_source->value = $source;
            $slider_source->save();
        }

        if (isset($articles)) {
            $aids = explode(',', $articles);
            $aids = array_filter($aids, function($one) {
                return is_numeric($one);
            });
            $activites->value = implode(',', $aids);
            $activites->save();
        }

        if (isset($captcha)) {
            $is_captcha->value = $captcha;
            $is_captcha->save();
        }

        return json_encode(array("success" => true, "info" => "保存成功！"));
   
    }

}
