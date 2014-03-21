<?php

/** 
 * 管理员登录页面控制器
 * @author shonenada
 *
 */

use Model\Menu;
use Model\Setting;

return array(
    "export" => function($app) {

        // 渲染管理员首页
        $app->get("/admin", function() use($app) {
            $user = \GlobalEnv::get('user');
            if ($user == NULL) {
                return $app->redirect('/admin/signin');
            }
            else {
                return $app->redirect('/admin/content');
            }
        });

        $app->get('/admin/setting', function () use ($app) {
            $slider_nums = Setting::get('index_slider', 'nums');
            $slider_fresh_time = Setting::get('index_slider', 'fresh_time');
            $slider_source = Setting::get('index_slider', 'source');
            $menu_list = Menu::findBy(array('is_hide' => '0', 'type' => '2'));
            return $app->render('admin/setting.html', get_defined_vars());
        });

        $app->post('/admin/setting/save', function() use ($app) {
            $slider_nums = Setting::findByKey('index_slider', 'nums');
            $slider_fresh_time = Setting::findByKey('index_slider', 'fresh_time');
            $slider_source = Setting::findByKey('index_slider', 'source');

            if ($nums = $app->request->params('index_slider_nums')) {
                $slider_nums->value = $nums;
                $slider_nums->save();
            }
            
            if ($freshtime = $app->request->params('index_slider_fresh_time')) {
                $slider_fresh_time->value = $freshtime;
                $slider_fresh_time->save();
            }
            
            if ($source = $app->request->params('index_slider_source')) {
                $slider_source->value = $source;
                $slider_source->save();
            }

            return json_encode(array("success" => true, "info" => "保存成功！"));
        });

    }
);
