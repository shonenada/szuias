<?php

namespace Controller\Admin;

use \Model\User;
use \Model\Setting as SettingModel;

class SignIn extends \Controller\Base {

    static public $url = '/admin/signin';

    static public function get () {
        $is_captcha = SettingModel::get('admin_signin', 'captcha');
        return self::render("admin/signin.html", get_defined_vars());
    }

    static public function post () {
        // 验证用户输入，检测权限。
        // 获取用户输入的表单信息
        $username = self::$request->post('username');
        $rawPasswd = self::$request->post('password');
        $captchaCode = self::$request->post('captchaCode');
        $is_captcha = SettingModel::get('admin_signin', 'captcha');

        if (empty($username) || empty($rawPasswd) || (empty($captchaCode) && $is_captcha)) {
            return self::render('admin/signin.html', array('errors' => '请输入信息', 'is_captcha' => $is_captcha));
        }

        // 检查验证码是否正确
        if ((empty($_SESSION['captchaCode']) || empty($captchaCode) || strtolower($captchaCode) != $_SESSION['captchaCode']) && $is_captcha) {
            return self::render('admin/signin.html', array('errors' => '验证码不正确', 'is_captcha' => $is_captcha));
        }

        // 验证用户
        $salt = self::$app->config('salt');
        $user = User::findByUsername($username);
        if ($user == null || !($user->checkPassword($rawPasswd, $salt))) {
            return self::render('admin/signin.html', array('errors' => '用户名或密码错误', 'is_captcha' => $is_captcha));
        }

        $now = new \DateTime('now', new \DateTimezone('Asia/Shanghai'));
        $ip = self::$request->getIp();
        $token = \Util\Encryption::generateToken($ip, $now, $salt);
        $user->setToken($token);
        $user->setIp($ip);
        $user->setLastLogin($now);
        $user->save();
        $user->flush();
        self::$app->setEncryptedCookie('user_id', $user->getId());
        self::$app->setEncryptedCookie('token', $token);
        return self::redirect(self::urlFor('admin_index_get'));

    }
}
