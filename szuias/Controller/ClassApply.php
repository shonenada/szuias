<?php

namespace Controller;

use \Model\ClassApplication;

class ClassApply extends Base {

    static public $url = '/apply';

    static public function get() {
        $show_identity = true;
        $identity = self::$request->get('identity');
        $application = ClassApplication::findOneBy(array('identity' => $identity));
        if ($application != NULL || is_numeric($identity)) {
            $show_identity = false;
        }
        return self::render("class_application.html", get_defined_vars());
    }

    static public function post() {
        $post = self::$request->post();
        $tips = '';
        $success = true;
        $identity = self::$request->post('identity');
        $apply = ClassApplication::findOneBy(array('identity' => $identity));
        if ($apply == NULL) {
            $apply = new ClassApplication();
        }

        $not_empty = array(
            array('name', '姓名'),
            array('gender', '性别'),
            array('birthday', '出生日期'),
            array('identity', '身份证号'),
            array('middle_school', '毕业中学'),
            array('exam_id', '准考证号'),
            array('phone', '手机号码'),
            array('email', 'Email'),
            array('intro', '个人简介'),
            array('reason', '报考理由'),
            array('total', '高考总分'),
            array('chinese', '语文'),
            array('math', '数学'),
            array('english', '英语'),
            array('science', '理综'),
            array('source', '生源地'),
            array('score_line', '生源地一本分数线'),
            array('parent', '家长姓名'),
            array('relationship', '关系'),
            array('parent_phone', '联系电话'),
            array('parent_addr', '通信地址'),
        );

        foreach($not_empty as $field) {
            if (!isset($post[$field[0]]) || empty($post[$field[0]])) {
                $success = false;
                $tips = $field[1] . '不能为空';
            }
        }

        if ($success) {
            $numeric = array(
                array('identity', '身份证号'),
                array('phone', '手机号码'),
                array('total', '高考总分'),
                array('chinese', '语文成绩'),
                array('math', '数学成绩'),
                array('english', '英语成绩'),
                array('science', '理综成绩'),
                array('parent_phone', '家长联系电话'),
            );

            foreach($numeric as $field) {
                if (!is_numeric($post[$field[0]])) {
                    $success = false;
                    $tips = $field[1] . '必须为数字';
                }
            }
        }

        if ($success) {
            $result = \Util\Upload::uploadApplicationPhoto();
            if ($result['error'] == 1) {
                $tips = $result['message'];
                $success = false;
            }
        }

        if ($success) {
            $apply->name = $post['name'];
            $apply->photo = $result['url'];
            $apply->gender = $post['gender'];
            $apply->birthday = \DateTime::createFromFormat('Y-m-d', $post['birthday']);
            $apply->identity = $post['identity'];
            $apply->middle_school = $post['middle_school'];
            $apply->exam_id = $post['exam_id'];
            $apply->major = $post['major'];
            $apply->stu_id = $post['stu_id'];
            $apply->phone = $post['phone'];
            $apply->email = $post['email'];
            $apply->addr = $post['addr'];
            $apply->total = $post['total'];
            $apply->chinese = $post['chinese'];
            $apply->math = $post['math'];
            $apply->english = $post['english'];
            $apply->science = $post['science'];
            $apply->student_source = $post['source'];
            $apply->score_line = $post['score_line'];
            $apply->intro = $post['intro'];
            $apply->reason = $post['reason'];
            $apply->parent_name = $post['parent'];
            $apply->relation = $post['relationship'];
            $apply->parent_phone = $post['parent_phone'];
            $apply->parent_addr = $post['parent_addr'];
            $apply->save();
        }

        return json_encode(array('success' => $success, 'info' => $tips));
    }
}
