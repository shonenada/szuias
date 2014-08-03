<?php

/**
 * 定义系统入口工厂函数
 * @author shonenada
 *
 */

// 加载所有控制器
// key: 控制器名称
// value: 控制器路径，所有控制防放置于 controllers/ 目录中。
// 请不要使用函数自动读取 controllers/ 目录中
// 的控制器，Explicit is better than impliccit. (Thanks bcho)
$controllers = array (
    'master_app' => 'Master',
    'lang_app' => 'Lang',
    'search_app' => 'Search',
    'captcha_app' => 'Captcha',
    'menu_show_app' => 'MenuShow',
    'menu_list_app' => 'MenuList',
    'article_app' => 'Article',
    'class_apply' => 'ClassApply',
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
    'admin_category_delete_app' => 'Admin.CategoryDelete',
    'admin_content_app' => 'Admin.Content',
    'admin_content_view_app' => 'Admin.ContentView',
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
    'admin_menu_view_app' => 'Admin.MenuView',
    'admin_menu_edit_app' => 'Admin.MenuEdit',
    'admin_menu_create_app' => 'Admin.MenuCreate',
    'admin_menu_create_sub_app' => 'Admin.MenuCreateSub',
    'admin_menu_delete_app' => 'Admin.MenuDelete',
    'admin_menu_save_app' => 'Admin.MenuSave',
    'admin_profile_app' => 'Admin.Profile',
    'admin_profile_edit_app' => 'Admin.ProfileEdit',
    'admin_profile_password_app' => 'Admin.ProfilePassword',
    'admin_setting_app' => 'Admin.Setting',
    'admin_setting_save_app' => 'Admin.SettingSave',
    'admin_setting_slier_save_app' => 'Admin.SettingSliderSave',
    'admin_setting_slier_create_app' => 'Admin.SettingSliderCreate',
    'admin_setting_slier_delete_app' => 'Admin.SettingSliderDelete',
    'admin_application_app' => 'Admin.Application',
    'admin_application_show_app' => 'Admin.ApplicationShow',
    'admin_application_delete_app' => 'Admin.ApplicationDelete',
    'admin_sign_in_app' => 'Admin.SignIn',
    'admin_sign_out_app' => 'Admin.SignOut',
);

// 系统入口工厂函数
function createApp ($configFiles=array()) {

    if(!is_array($configFiles))
        exit('Config files are not array.');

    // 初始化 app
    $app = new Slimx();
    \GlobalEnv::set('app', $app);
    \Controller\Base::setApp($app);

    // 载入配置
    $config = require_once(APPROOT . 'config/common.php');
    $app->config($config);

    // 读取用户自定义的配置
    foreach($configFiles as $path)
        $app->config(require_once($path));

    \Extension\Auth::setup($app);
    \Extension\View::setup($app);
    \Extension\Middleware::setup($app);

    $translation_code = \Model\Lang::getByCode($app->config('translation.default.code'));
    \GlobalEnv::set('translation.default', $translation_code);
    \GlobalEnv::set('translation.default.id', $translation_code->id);
    \GlobalEnv::set('translation.default.code', $translation_code->code);

    // 注册控制
    global $controllers;
    registerControllers($app, $controllers);

    return $app;

}

// 注册控制器协助函数
function registerControllers ($app, $controllers){
    foreach ($controllers as $name => $path) {
        $app->registerController($path);
    }
}
