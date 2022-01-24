<?php

// @codingStandardsIgnoreFile

spl_autoload_register(function($class)
{
    if (0 === strpos($class, 'ML\\IRI\\')) {
        $path = implode('/', array_slice(explode('\\', $class), 2)).'.php';
        require_once __DIR__.'/../'.$path;
        return true;
    }
});
