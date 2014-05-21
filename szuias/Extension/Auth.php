<?php

namespace Extension;

class Auth {

    static public function setup($app) {
        $app->hook("slim.before.dispatch", function () use ($app){
            $uid = $app->getCookie("user_id");
            $ip = $app->request->getIp();
            $token = $app->getCookie("token");
            if (empty($uid)){
                $user = null;
            }else {
                $user = \Model\User::find($uid);
                if ($user && !($user->validateToken($token) && $user->validateIp($ip))) {
                    $user = null;
                }
            }
            \GlobalEnv::set('user', $user);
            View::addGlobalVariable($app, 'loggedUser', $user);

            $resource = $app->request->getPath();
            $method = $app->request->getMethod();
            $ptable = require(APPROOT . "permissions.php");
            $auth = new \RBAC\Authentication();
            $auth->load($ptable);
            if (!$auth->accessiable($user, $resource, $method)) {
                $app->halt(403, "Cookies is expired, please sign in again.");
            }
        });
    }

}