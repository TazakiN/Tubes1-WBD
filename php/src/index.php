<?php

use app\App;

spl_autoload_register(function ($className) {
    if (!str_starts_with($className, "app\\")) {
        return;
    }

    $className = substr($className, 4);
    $className = str_replace("\\", "/", $className);
    $class = __DIR__ . "/app/" . "{$className}.php";
    include_once($class);
});

session_start();
$app = new App();
