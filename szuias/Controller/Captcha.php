<?php

namespace Controller;

class Captcha extends Base {

    public static $name = 'captcha';

    static public function get() {
        $captcha = new \Captcha(90, 30, 4);
        $captcha->showImg();
        $_SESSION['captchaCode'] = strtolower($captcha->getCaptcha());
    }

}
