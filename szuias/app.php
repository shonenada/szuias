<?php

/**
 * 定义系统入口工厂函数
 * @author shonenada
 *
 */

use \Utils;

// 加载扩展函数
require_once(APPROOT . "extensions.php");

// 导入网站配置文件
$config = require_once(APPROOT . 'config/config.php');

// 加载所有控制器
// key: 控制器名称
// value: 控制器路径，所有控制防放置于 controllers/ 目录中。
// 请不要使用函数自动读取 controllers/ 目录中
// 的控制器，Explicit is better than impliccit. (Thanks bcho)
$controllers = array (
    'master_app' => 'Master',
    'captcha_app' => 'Captcha',
    'menu_show_app' => 'MenuShow',
    'menu_list_app' => 'MenuList',
    'article_app' => 'Article',
    'file_upload_app' => 'FileUpload',
    'file_manager_app' => 'FileManager',
    'admin_account_app' => 'Admin.Account',
    'admin_account_create_app' => 'Admin.AccountCreate',
    'admin_account_delete_app' => 'Admin.AccountDelete',
    'admin_account_permission_app' => 'Admin.AccountPermission',
    'admin_account_permission_save_app' => 'Admin.AccountPermissionSave',
    'admin_account_reset_app' => 'Admin.AccountReset',
    'admin_category_app' => 'Admin.Category',
    'admin_category_save_app' => 'Admin.CategorySave',
    'admin_content_app' => 'Admin.Content',
    'admin_content_save_app' => 'Admin.ContentSave',
    'admin_content_create_app' => 'Admin.ContentCreate',
    'admin_content_delete_app' => 'Admin.ContentDelete',
    'admin_content_edit_app' => 'Admin.ContentEdit',
    'admin_content_hide_create_app' => 'Admin.ContentHideCreate',
    'admin_content_hide_delete_app' => 'Admin.ContentHideDelete',
    'admin_content_search_app' => 'Admin.ContentSearch',
    'admin_content_top_create_app' => 'Admin.ContentTopCreate',
    'admin_content_top_delete_app' => 'Admin.ContentTopDelete',
    'admin_data_app' => 'Admin.Data',
    'admin_data_backup_app' => 'Admin.DataBackup',
    'admin_data_delete_app' => 'Admin.DataDelete',
    'admin_data_recover_app' => 'Admin.DataRecover',
    'admin_index_app' => 'Admin.Index',
    'admin_menu_app' => 'Admin.Menu',
    'admin_menu_delete_app' => 'Admin.MenuDelete',
    'admin_menu_save_app' => 'Admin.MenuSave',
    'admin_profile_app' => 'Admin.Profile',
    'admin_profile_edit_app' => 'Admin.ProfileEdit',
    'admin_profile_password_app' => 'Admin.ProfilePassword',
    'admin_setting_app' => 'Admin.Setting',
    'admin_setting_save_app' => 'Admin.SettingSave',
    'admin_sign_in_app' => 'Admin.SignIn',
    'admin_sign_out_app' => 'Admin.SignOut',
);

// 系统入口工厂函数
function create_app ($config_files=array()) {
    if(!is_array($config_files))
        exit('Config ciles are not array.');

    // 初始化 app
    $app = new Slimx();
    \GlobalEnv::set('app', $app);
    \Controller\Base::setApp($app);

    // 载入配置
    global $config;
    $app->config($config);

    // 读取用户自定义的配置
    foreach($config_files as $cfil)
        $app->config(require_once($cfil));

    // 安装钩子
    setup_hooks($app);
    // 安装 Twig 视图引擎
    setup_views($app);
    // 导入视图全局变量
    setup_view_globals($app);

    // 安装中间件
    setup_middleware($app);

    // 注册控制
    global $controllers;
    Utils::registerControllers($app, $controllers);

    return $app;
}
