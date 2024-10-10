<?php

namespace app;

class Request
{
    public static function getURL()
    {
        return parse_url($_SERVER['REQUEST_URI'])['path'];
    }

    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getParams()
    {
        $params = [];
        foreach ($_GET as $key => $value) {
            $params[$key] = $value;
        }
        return $params;
    }
}
