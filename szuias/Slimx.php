<?php

/**
 * \Slim\Slim 扩展类
 * @author shonenada
 * 
 */


class Slimx extends \Slim\Slim {

    // 重写 mapRoute，适应 Routex 类。
    protected function mapRoute($args)
    {
        $pattern = array_shift ($args);
        $callable = array_pop ($args);
        $route = new Routex ($pattern, $callable, $this->settings['routes.case_sensitive']);
        $this->router->map ($route);
        if (count($args) > 0) {
            $route->setMiddleware ($args);
        }

        return $route;
    }

    // 注册控制器方法，将 php 控制注册到 app 内部，并安装控制器。
    public function registerController ($controller) {
        $controller = str_replace('.', '\\', $controller);
        $cls = "\Controller\\$controller";
        $vars = get_class_vars($cls);

        if (array_key_exists('url', $vars)) 
            $url = $vars['url'];
        else
            $url = '/' . strtolower(str_replace('\\', '/', $controller));

        if (method_exists($cls, 'get'))
            $handler = $this->get($url, "$cls::_get");

        if (method_exists($cls, 'post'))
            $handler = $this->post($url, "$cls::_post");

        if (method_exists($cls, 'put'))
            $handler = $this->put($url, "$cls::_put");

        if (method_exists($cls, 'delete'))
            $handler = $this->delete($url, "$cls::_delete");

        if (array_key_exists('conditions', $vars))
            $handler->conditions($vars['conditions']);

        if (array_key_exists('name', $vars))
            $handler->name($vars['name']);
        else
            $handler->name(strtolower($controller));

    }



}