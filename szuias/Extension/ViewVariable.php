<?php

namespace Extension;

class ViewVariable {

    static public $vars = null;

    static public function init() {
        static::$vars = array(
            'siteTitle' => '高等研究院 - 深圳大学',
            'langCode' => \GlobalEnv::get('app')->getCookie('lang.code'),
            'globalTopMenus' => \Model\Menu::getTopMenus($all=false),
        );
    }

}
ViewVariable::init();
