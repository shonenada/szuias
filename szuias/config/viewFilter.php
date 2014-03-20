<?php

use \Twig_SimpleFilter;

$ellipsis = new Twig_SimpleFilter('ellipsis', function($string, $maxLength, $ellipsisStr='...') {
    if (mb_strlen($string, 'utf-8') > $maxLength) {
        return mb_substr($string, 0, $maxLength, 'utf-8') . $ellipsisStr;
    }
    return $string;
});

return array(
    $ellipsis,
);