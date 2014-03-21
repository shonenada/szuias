<?php

/** 
 * 管理员登录页面控制器
 * @author shonenada
 *
 * TODO: 视图逻辑与业务逻辑杂糅，验证也杂糅了。。
 *
 */

use \Model\User;
use \Model\Menu;

return array(
    "export" => function($app) {

        // 渲染登录页面模版
        $app->get("/admin/signin", function() use($app) {
            $app->render("admin/signin.html", get_defined_vars());
        });

        // 验证用户输入，检测权限。
        $app->post('/admin/signin', function() use($app) {
            // 获取用户输入的表单信息
            $username = $app->request->params('username');
            $rawPasswd = $app->request->params('password');
            $captchaCode = $app->request->params('captchaCode');

            if (empty($username) || empty($rawPasswd) || empty($captchaCode)) {
                return $app->render('admin/signin.html', array('errors' => '请输入信息'));
            }

            // 检查验证码是否正确
            if (empty($_SESSION['captchaCode']) || empty($captchaCode) || strtolower($captchaCode) != $_SESSION['captchaCode']) {
                return $app->render('admin/signin.html', array('errors' => '验证码不正确'));
            }

            // 验证用户
            $salt = $app->config('salt');
            $user = User::findByUsername($username);
            if ($user == null || !($user->checkPassword($rawPasswd, $salt))) {
                return $app->render('admin/signin.html', array('errors' => '用户名或密码错误'));
            }

            if (!$user->isAdmin()) {
                return $app->render('admin/signin.html', array('errors' => '验证失败'));
            }

            $now = new DateTime('now', new DateTimezone('Asia/Shanghai'));
            $ip = $app->request->getIp();
            $token = Utils::generateToken($ip, $now, $salt);
            $user->setToken($token);
            $user->setIp($ip);
            $user->setLastLogin($now);
            $user->save();
            $user->flush();
            $app->setEncryptedCookie('user_id', $user->getId());
            $app->setEncryptedCookie('token', $token);
            return $app->redirect('/admin');

        });

        $app->get('/admin/signout', function() use ($app) {
            $app->deleteCookie('user_id');
            $app->deleteCookie('token');
            return $app->redirect('/admin/signin');
        });

    }
);
