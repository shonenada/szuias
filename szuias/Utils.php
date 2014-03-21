<?php

/**
 * 定义工具类
 * @author shonenada
 *
 */


use \Model\File;


class Utils {

    // 生成 token 静态方法
    static public function generateToken($ip, $now, $salt){
        $now = $now->format('Y-m-d H:i:s');
        return md5 ("{$now}{{$salt}}{$ip}");
    }

    // 根据控制器名请求控制器。例如：
    //     控制器为 controllers/master/home.php
    //     <?php Utils::requireController('master/home');
    static public function requireController ($controllerName) {
        return require_once (APPROOT . "controllers/{$controllerName}.php");
    }

    // 注册控制器协助函数
    static function registerControllers($app, $controllers){
        foreach ($controllers as $name => $path) {
            $controller = Utils::requireController($path);
            $app->registerController($controller);
        }
    }

    static public function filemanager($dir_name='', $path='', $order='') {
        $root_path = dirname($_SERVER['SCRIPT_FILENAME']) . '/uploads/';
        $root_path = realpath($root_path) . '/';
        $root_url = substr($_SERVER['SCRIPT_NAME'], 0, -9) . 'uploads/';

        $ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
        if (!in_array($dir_name, array('', 'image', 'flash', 'media', 'file'))) {
            return false;
        }
        if ($dir_name !== '') {
            $root_path .= $dir_name . "/";
            $root_url .= $dir_name . "/";
        }           
        //根据path参数，设置各路径和URL
        if (empty($path)) {
            $current_path = realpath($root_path) . '/';
            $current_url = $root_url;
            $current_dir_path = '';
            $moveup_dir_path = '';
        } else {
            $current_path = realpath($root_path) . '/' . $path;
            $current_url = $root_url . $path;
            $current_dir_path = $path;
            $moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
        }
        // echo realpath($root_path);
        //排序形式，name or size or type
        $order = empty($order) ? 'name' : strtolower($order);

        if (preg_match('/\.\./', $current_path)) {
            return false;
        }

        if (!preg_match('/\/$/', $current_path)) {
            return false;
        }

        if (!file_exists($current_path) || !is_dir($current_path)) {
            return false;
        }

        //遍历目录取得文件信息
        $file_list = array();
        if ($handle = opendir($current_path)) {
            $i = 0;
            while (false !== ($filename = readdir($handle))) {
                if ($filename{0} == '.') continue;
                $file = $current_path . $filename;
                if (is_dir($file)) {
                    $file_list[$i]['is_dir'] = true; //是否文件夹
                    $file_list[$i]['has_file'] = (count(scandir($file)) > 2); //文件夹是否包含文件
                    $file_list[$i]['filesize'] = 0; //文件大小
                    $file_list[$i]['is_photo'] = false; //是否图片
                    $file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
                } else {
                    $file_list[$i]['is_dir'] = false;
                    $file_list[$i]['has_file'] = false;
                    $file_list[$i]['filesize'] = filesize($file);
                    $file_list[$i]['dir_path'] = '';
                    $temp_arr = explode('.', trim($filename));
                    $file_ext = strtolower(array_pop($temp_arr));
                    $file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
                    $file_list[$i]['filetype'] = $file_ext;
                }
                $file_list[$i]['filename'] = $filename; //文件名，包含扩展名
                $file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
                $i++;
            }
            closedir($handle);
        }
        
        //排序
        function cmp_func($a, $b) {
            global $order;
            if ($a['is_dir'] && !$b['is_dir']) {
                return -1;
            } else if (!$a['is_dir'] && $b['is_dir']) {
                return 1;
            } else {
                if ($order == 'size') {
                    if ($a['filesize'] > $b['filesize']) {
                        return 1;
                    } else if ($a['filesize'] < $b['filesize']) {
                        return -1;
                    } else {
                        return 0;
                    }
                } else if ($order == 'type') {
                    return strcmp($a['filetype'], $b['filetype']);
                } else {
                    return strcmp($a['filename'], $b['filename']);
                }
            }
        }
        usort($file_list, 'cmp_func');

        $result = array();
        //相对于根目录的上一级目录
        $result['moveup_dir_path'] = $moveup_dir_path;
        //相对于根目录的当前目录
        $result['current_dir_path'] = $current_dir_path;
        //当前目录的URL
        $result['current_url'] = $current_url;
        //文件数
        $result['total_count'] = count($file_list);
        //文件列表数组
        $result['file_list'] = $file_list;

        return $result;
    }

    static public function upload($dir_name = 'image', $timestamp = 0, $size = 1000000){

        function alert($msg) {
            return json_encode(array('error' => true, 'message' => $msg));
        }

        function insert_into_database($arr) {
            $init = array('article_id' => 0, 'real_name' => '', 'address' => '',
                'type' => '', 'file_size' => 0, 'uploader_id' => 0);
            $arr = array_merge($init, $arr);
            $file = new File();
            $file->populate_from_array($arr);

            if (empty($_SESSION['upload_buffer'])){
                $upload_buffer = array();
            } else {
                $upload_buffer = $_SESSION['upload_buffer'];
            }
            if (!isset($upload_buffer)) {
                $upload_buffer = array();
            }
            array_push($upload_buffer, $file);
            $_SESSION['upload_buffer'] = $upload_buffer;
        }

        $ext_arr = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2',
                            'gif', 'jpg', 'jpeg', 'png'),
        );

        $max_size = $size;
        //文件保存目录路径
        $save_path = dirname($_SERVER['SCRIPT_FILENAME']) . '/uploads/';
        //文件保存目录URL
        $save_url = substr($_SERVER['SCRIPT_NAME'], 0, -9) . 'uploads/';

        $save_path = realpath($save_path) . '/';
        //PHP上传失败
        if (!empty($_FILES['imgFile']['error'])) {
            switch($_FILES['imgFile']['error']){
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '图片只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择图片。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = 'File upload stopped by extension。';
                    break;
                case '999':
                default:
                    $error = '未知错误。';
            }
            return array('error' => 1, 'message' => $error);
        }
        //有上传文件时
        if (empty($_FILES) === false) {
            //原文件名
            $file_name = $_FILES['imgFile']['name'];
            //服务器上临时文件名
            $tmp_name = $_FILES['imgFile']['tmp_name'];
            //文件大小
            $file_size = $_FILES['imgFile']['size'];
            //检查文件名
            if (!$file_name) {
                return alert("请选择文件。");
            }
            //检查目录
            if (is_dir($save_path) === false) {
                return alert("上传目录不存在。");
            }
            //检查目录写权限
            if (is_writable($save_path) === false) {
                return alert("上传目录没有写权限。");
            }
            //检查是否已上传
            if (is_uploaded_file($tmp_name) === false) {
                return alert("上传失败。");
            }
            //检查文件大小
            if ($file_size > $max_size) {
                return alert("上传文件大小超过限制。");
            }
            //检查目录名
            $dir_name = trim($dir_name);
            if (empty($ext_arr[$dir_name])) {
                return alert("目录名不正确。");
            }
            //获得文件扩展名
            $temp_arr = explode(".", $file_name);
            $file_ext = array_pop($temp_arr);
            $file_ext = trim($file_ext);
            $file_ext = strtolower($file_ext);
            //检查扩展名
            if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
                return alert("只允许上传" . implode("，", $ext_arr[$dir_name]) . "格式的图片。");
            }
            //附件表记录
            $address = $dir_name . "/";
            //创建文件夹
            if ($dir_name !== '') {
                $save_path .= $dir_name . "/";
                $save_url .= $dir_name . "/";
                if (!file_exists($save_path)) {
                    mkdir($save_path);
                }
            }
            $ym = date("Ym");
            $address .= $ym . "/";
            $save_path .= $ym . "/";
            $save_url .= $ym . "/";
            if (!file_exists($save_path)) {
                mkdir($save_path);
            }
            //新文件名
            $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
            $address .= $new_file_name;
            //移动文件
            $file_path = $save_path . $new_file_name;
            if (move_uploaded_file($tmp_name, $file_path) === false) {
                return alert("上传文件失败。");
            }
            //上传成功，附件表插入记录
            $arr = array(
                'real_name' => $file_name,
                'address' => $address,
                'type' => $file_ext,
                'file_size' => floor($file_size/1024),
                'uploader_id' => \GlobalEnv::get('user')->getId());
            insert_into_database($arr);
            @chmod($file_path, 0644);
            $file_url = $save_url . $new_file_name;
            return array('error' => 0, 'url' => $file_url);
        }
    }

}