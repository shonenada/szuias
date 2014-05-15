<?php

namespace Extension;

class Middleware {

    static public function setup($app) {
        $app->add(new \Slim\Middleware\SessionCookie());
    }

}