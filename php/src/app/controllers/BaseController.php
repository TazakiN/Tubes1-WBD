<?php

namespace app\controllers;

use app\exceptions\MethodNotAllowedException;

abstract class BaseController
{
    protected $service;

    protected function __construct($service)
    {
        $this->service = $service;
    }

    protected function get($urlParams)
    {
        throw new MethodNotAllowedException("Method not allowed");
    }
    protected function post($urlParams)
    {
        throw new MethodNotAllowedException("Method not allowed");
    }
    protected function put($urlParams)
    {
        throw new MethodNotAllowedException("Method not allowed");
    }
    protected function delete($urlParams)
    {
        throw new MethodNotAllowedException("Method not allowed");
    }

    public function handle($method, $urlParams)
    {
        $to_lower_method = strtolower($method);
        echo $this->$to_lower_method($urlParams);
    }

    protected static function render($data, $view, $layout)
    {
        extract($data);
        ob_start();
        include_once __DIR__ . "/../views/{$view}.php";
        $content = ob_get_clean();

        $data["__content"] = $content;
        extract($data);
        include_once __DIR__ . "/../views/{$layout}.php";
    }

    protected static function redirect($url, $data = [], $statusCode = 303)
    {
        $params = "";
        foreach ($data as $key => $value) {
            $params .= "$key=$value&";
        }
        header('Location: ' . $url . "?" . $params, true, $statusCode);
    }

    protected function getToastContent($urlParams, $data = []) {
        $data['success'] = $urlParams['success'] ?? null;
        $data['warning'] = $urlParams['warning'] ?? null;
        $data['error'] = $urlParams['error'] ?? null;
        $data['help'] = $urlParams['help'] ?? null;
        return $data;
    }
}
