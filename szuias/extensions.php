<?php

/**
 * 定义系统入口工厂函数
 * @author shonenada
 *
 */

use Model\User;
use Model\Permission;
use RBAC\Authentication;


// 安装 Twig 视图引擎
function setupViews ($app) {
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
function setupViewGlobals ($app) {
    $viewEnv = $app->view()->getEnvironment();
    
    $globals = require_once(APPROOT . 'config/viewGlobal.php');
    foreach ($globals as $key => $value) {
        $viewEnv->addGlobal($key, $value);
    }

    $funcs = require_once(APPROOT . 'config/viewFunc.php');
    foreach ($funcs as $func) {
        $viewEnv->addFunction($func);
    }

    $filters = require_once(APPROOT . 'config/viewFilter.php');
    foreach ($filters as $filter) {
        $viewEnv->addFilter($filter);
    }
}


// 安装中间件
function setupMiddleware ($app) {
    $app->add(new \Slim\Middleware\SessionCookie());
}


// 添加视图全局变量。例如：
// <?php addGlobalViewVariable ($app, 'webTitle', 'SzuIAS');
// html: {{ webTitle }}
function addGlobalViewVariable ($app, $key, $value) {
    $view = $app->view();
    $twigEnv = $view->getEnvironment();
    $twigEnv->addGlobal($key, $value);
}


// 将 权限验证 (RBAC) 添加到 app 的钩子数字中。
function permissionCheckHook ($app) {
    $app->hook("slim.before.router", function () use ($app){

        $uid = $app->getCookie("user_id");
        $ip = $app->request->getIp();
        $token = $app->getCookie("token");
        if (empty($uid)){
            $user = null;
        }else {
            $user = User::find($uid);
            if ($user && !($user->validateToken($token) && $user->validateIp($ip))) {
                $user = null;
            }
        }
        \GlobalEnv::set('user', $user);
        addGlobalViewVariable($app, 'loggedUser', $user);
    });

    $app->hook("slim.before.dispatch", function () use ($app){
        $user = \GlobalEnv::get('user');
        $resource = $app->request->getPath();
        $method = $app->request->getMethod();
        $ptable = require(APPROOT . "permissions.php");
        $auth = new Authentication();
        $auth->load($ptable);
        if (!$auth->accessiable($user, $resource, $method)) {
            $app->halt(403, "Cookies is expired, please sign in again.");
        }
    });
}


// 安装钩子。
function setupHooks ($app) {
    permissionCheckHook($app);
}
