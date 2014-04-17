<?php

namespace Controller\Admin;

use \Model\Permission;
use \Model\Category as CategoryModel;

class CategoryDelete extends AdminBase {

    static public $url = '/admin/category/delete';

    static public function post () {
        $cid = self::$request->post('cid');
        $category = CategoryModel::find($cid);
        if ($category == null) {
            return json_encode(array('success' => false, 'info' => '对象不存在'));
        }
        foreach ($category->articles as $a){
            $a->delete();
        }
        $category->delete();
        return json_encode(array('success' => true, 'info' => '删除成功!'));
    }

}
