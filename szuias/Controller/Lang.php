<?php

namespace Controller;

class Lang extends Base {

    static public function post() {
        $lang = self::$request->post('lang');
        if (in_array($lang, array('zh', 'en'))) {
            self::$app->setCookie('lang', $lang);
        }
        return self::$app->redirect('/');
    }

}
