<?php

namespace Controller;

use \Model\ClassApplication;

class ClassApply extends Base {

    static public $url = '/apply';

    static public function get() {
        $identity_error = false;
        $show_identity = true;
        $identity = self::$request->get('identity');
        $application = ClassApplication::findOneBy(array('identity' => $identity));
        if ($application != NULL || (is_numeric(substr($identity, 0, 16)) && strlen($identity) == 18)) {
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
            'name',
            'gender',
            'birthday',
            'identity',
            'middle_school',
            'exam_id',
            'phone',
            'email',
            'intro',
            'reason',
            'total',
            'chinese',
            'math',
            'english',
            'source',
            'score_line',
            'parent',
            'relationship',
            'parent_phone',
            'parent_addr',
        );

        $not_empty_errors = array();

        foreach($not_empty as $field) {
            if (!isset($post[$field]) || empty($post[$field])) {
                $success = false;
                $not_empty_errors[] = $field;
            }
        }

        if ($success) {
            $numeric = array(
                'phone',
                'total',
                'chinese',
                'math',
                'english',
                'science',
                'parent_phone',
            );

            $numeric_errors = array();

            foreach($numeric as $field) {
                if (!is_numeric($post[$field])) {
                    $success = false;
                    $numeric_errors[] = $field;
                }
            }
        }

        if ($success) {
            $result = \Util\Upload::uploadApplicationPhoto();
            // if ($result['error'] == 1) {
                // $tips = $result['message'];
                // $success = false;
            // }
        }

        if ($success) {
            $apply->name = $post['name'];
            if (isset($result['url']) && !empty($result['url'])) {
                $apply->photo = $result['url'];
            }
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
        if (!$success) {
            return self::render("class_application.html", get_defined_vars());
        }
        else {
            return '<html><head><script>alert("提交成功！");location.href="/";</script></head></html>';
        }
        // return json_encode(array('success' => $success, 'info' => $tips));
    }
}
