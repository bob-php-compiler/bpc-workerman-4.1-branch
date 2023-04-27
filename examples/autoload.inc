<?php

spl_autoload_register(function ($class) {
    if (defined('__BPC__')) {
        include_silent(str_replace('\\', '/', $class) . '.php');
    } else {
        if ($class[0] == '\\') {
            $class = substr($class, 1);
        }
        if (strncmp($class, 'Workerman\\', 10) == 0) {
            $file = __DIR__ . '/../' . str_replace('\\', '/', substr($class, 10)) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        }
    }
});
