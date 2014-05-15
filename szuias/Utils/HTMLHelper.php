<?php

/**
 * HTML helper
 * @author shonenada
 *
 */

namespace Utils;

class HTMLHelper {
    
    static public function getTeacherImg ($input) {
        $teacher_imgs = array();
        preg_match("/<[img|IMG][^>]+src=[\'\"](?<url>[\S]+)[\'\"][^>]+>/", $input, $teacher_imgs);
        if (isset($teacher_imgs['url']))
            return $teacher_imgs['url'];
        else
            return null;
    }

    static public function removeHTML($input) {
        $no_html = preg_replace("|<[^>]+>|", '', $input);
        return $no_html;
    }
    
}