<?php

namespace Controller;

class Lang extends Base {

    static public function get() {
        $lang = self::$request->get('lang');
        $next = self::$request->get('next');
        if (in_array($lang, array('zh', 'en'))) {
            self::$app->setCookie('lang.code', $lang);
        }
        return self::$app->redirect($next);
    }

}
