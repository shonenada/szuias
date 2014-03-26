<?php

namespace Controller\Admin;
use \Model\User;

class SignIn extends \Controller\Base {

    static public $url = '/admin/signin';

    static public function get () {
        return self::render("admin/signin.html", get_defined_vars());
    }

    static public function post () {
        // 验证用户输入，检测权限。
        // 获取用户输入的表单信息
        $username = self::$request->post('username');
        $rawPasswd = self::$request->post('password');
        $captchaCode = self::$request->post('captchaCode');

        if (empty($username) || empty($rawPasswd) || empty($captchaCode)) {
            return self::render('admin/signin.html', array('errors' => '请输入信息'));
        }

        // 检查验证码是否正确
        if (empty($_SESSION['captchaCode']) || empty($captchaCode) || strtolower($captchaCode) != $_SESSION['captchaCode']) {
            return self::render('admin/signin.html', array('errors' => '验证码不正确'));
        }

        // 验证用户
        $salt = self::$app->config('salt');
        $user = User::findByUsername($username);
        if ($user == null || !($user->checkPassword($rawPasswd, $salt))) {
            return self::render('admin/signin.html', array('errors' => '用户名或密码错误'));
        }

        $now = new \DateTime('now', new \DateTimezone('Asia/Shanghai'));
        $ip = self::$request->getIp();
        $token = \Utils::generateToken($ip, $now, $salt);
        $user->setToken($token);
        $user->setIp($ip);
        $user->setLastLogin($now);
        $user->save();
        $user->flush();
        self::$app->setEncryptedCookie('user_id', $user->getId());
        self::$app->setEncryptedCookie('token', $token);
        return self::redirect(self::urlFor('admin_index'));

    }
}
