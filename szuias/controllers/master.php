<?php

use \Captcha;

return array(
    "export" => function($app) {
        $app->get("/", function() use($app) {
            $app->render("index.html", get_defined_vars());
        });
        $app->get('/captcha', function () {
            $captcha = new Captcha(90, 30, 4);
            $captcha->showImg();
            $_SESSION['captchaCode'] = strtolower($captcha->getCaptcha());
        });
    }
);
