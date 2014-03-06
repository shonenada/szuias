<?php

/**
 * 定义系统入口工厂函数
 * @author shonenada
 *
 */

use Model\User;
use RBAC\Authentication;


// 安装 Twig 视图引擎
function setup_views ($app) {
    $view = $app->view();
    $view->setTemplatesDirectory($app->config('templates.path'));

    $view_options = require_once(APPROOT. 'config/view.php');
    $view->parserOptions = $view_options;

    $view->parserExtensions = array(
        new \Slim\Views\TwigExtension(),
    );

    $twigEnv = $view->getEnvironment();
}


// 导入视图全局变量
function setup_view_globals ($app) {
    $globals = require_once(APPROOT . 'config/viewGlobal.php');
    $viewEnv = $app->view()->getEnvironment();
    foreach ($globals as $key => $value) {
        $viewEnv->addGlobal($key, $value);
    }
}


// 安装中间件
function setup_middleware ($app) {
    $app->add(new \Slim\Middleware\SessionCookie());
}


// 添加视图全局变量。例如：
// <?php add_global_view_variable ($app, 'webTitle', 'SzuIAS');
// html: {{ webTitle }}
function add_global_view_variable ($app, $key, $value) {
    $view = $app->view();
    $twigEnv = $view->getEnvironment();
    $twigEnv->addGlobal($key, $value);
}


// 将 权限验证 (RBAC) 添加到 app 的钩子数字中。
function permission_check_hook ($app) {
    $app->hook("slim.before.router", function () use ($app){
        $salt = $app->config("salt");
        $uid = $app->getCookie("user_id");
        $token = $app->getCookie("token");
        if(isset($uid)){
            $u = User::find($uid);
            $user = User::validateToken($u, $token, $salt);
        } else {
            $user = NULL;
        }
        $app->environment['user'] = $user;
        add_global_view_variable($app, 'currentUser', $user);
    });

    $app->hook("slim.before.dispatch", function () use ($app){
        $user = $app->environment['user'];
        $resource = $app->request->getPath();
        $method = $app->request->getMethod();
        $ptable = require(APPROOT . "permissions.php");
        $auth = new Authentication();
        $auth->load($ptable);
        if (!$auth->accessiable($user, $resource, $method)) {
            $app->halt(403, "You have no permission!");
        }
    });
}


// 安装钩子。
function setup_hooks ($app) {
    permission_check_hook($app);
}
