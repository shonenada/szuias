<?php

namespace Controller;

class Captcha extends Base {

    static public function get() {
        $captcha = new \Util\Captcha(90, 30, 4);
        $captcha->showImg();
        $_SESSION['captchaCode'] = strtolower($captcha->getCaptcha());
    }

}
