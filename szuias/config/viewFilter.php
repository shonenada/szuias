<?php

use \Twig_SimpleFilter;

$ellipsis = new Twig_SimpleFilter('ellipsis', function($string, $maxLength, $ellipsisStr='...') {
    if (mb_strlen($string, 'utf-8') > $maxLength) {
        return mb_substr($string, 0, $maxLength, 'utf-8') . $ellipsisStr;
    }
    return $string;
});

$lang = new Twig_SimpleFilter('lang', function($obj, $field, $code) {
    $lang = \Model\Lang::getByCode($code);
    foreach ($obj->translations as $tran) {
        if ($tran->lang == $lang) {
            return $tran->$field;
        }
    }
    return '';
});

return array(
    $ellipsis,
    $lang,
);