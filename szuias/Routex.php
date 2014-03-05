<?php

/**
 * \Slim\Route 扩展类
 * @author shonenada
 * 
 */


class Routex extends \Slim\Route {

    // 重写 dispatch 方法
    // 直接输出控制器 return 的内容。
    public function dispatch()
    {
        foreach ($this->middleware as $mw) {
            call_user_func_array($mw, array($this));
        }

        $return = call_user_func_array($this->getCallable(), array_values($this->getParams()));
        echo $return;

        return ($return === false || $return === "") ? false : true;
    }

}