<?php

namespace Extension;

class View {

    static public function setup($app) {
        $view = $app->view();
        $view->setTemplatesDirectory($app->config('templates.path'));

        $view_options = require_once(APPROOT. 'config/view.php');
        $view->parserOptions = $view_options;

        $view->parserExtensions = array(
            new \Slim\Views\TwigExtension(),
        );

        $env = $app->view()->getEnvironment();

        static::setGlobalVariable($env);
        static::setGlobalFunction($env);
        static::setGlobalFilter($env);
    }

    static private function setGlobalVariable ($env) {
        foreach (ViewVariable::$vars as $key => $value) {
            $env->addGlobal($key, $value);
        }
    }

    static private function setGlobalFunction ($env) {
        foreach (ViewFunction::$functions as $func) {
            $env->addFunction($func);
        }
    }

    static private function setGlobalFilter ($env) {
        foreach (ViewFilter::$filters as $filter) {
            $env->addFilter($filter);
        }
    }

}