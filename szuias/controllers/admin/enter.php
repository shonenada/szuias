<?php

/** 
 * 管理员登录页面控制器
 * @author shonenada
 *
 * TODO: 视图逻辑与业务逻辑杂糅，验证也杂糅了。。
 *
 */

use \Model\User;

return array(
    "export" => function($app) {

        // 渲染登录页面模版
        $app->get("/admin/enter", function() use($app) {
            $app->render("admin/enter.html", get_defined_vars());
        });

        // 验证用户输入，检测权限。
        $app->post('/admin/enter', function() use($app) {
            // 获取用户输入的表单信息
            $username = $app->request->params('username');
            $rawPasswd = $app->request->params('password');
            $captchaCode = $app->request->params('captchaCode');

            if (empty($username) || empty($rawPasswd) || empty($captchaCode)) {
                return $app->render('admin/enter.html', array('errors' => '请输入信息'));
            }

            // 检查验证码是否正确
            if (empty($_SESSION['captchaCode']) || empty($captchaCode) || strtolower($captchaCode) != $_SESSION['captchaCode']) {
                return $app->render('admin/enter.html', array('errors' => '验证码不正确'));
            }

            // 验证用户
            $salt = $app->config('salt');
            $user = User::findByUsername($username);
            if ($user == null || !($user->checkPassword($rawPasswd, $salt))) {
                return $app->render('admin/enter.html', array('errors' => '用户或密码错误'));
            }

            return $app->redirect('/admin/dashborad');

        });

    }
);
