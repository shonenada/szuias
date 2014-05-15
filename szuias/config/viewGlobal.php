<?php

use \GlobalEnv;
use Model\Menu;

return array(
    'lang' => GlobalEnv::get('app')->getCookie('lang.code'),
    'siteTitle' => '高等研究院 - 深圳大学',
    'global_top_menus' => Menu::getTopMenus($all=false),
);