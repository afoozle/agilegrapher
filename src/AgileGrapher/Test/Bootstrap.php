<?php
namespace AgileGrapher\Test;
define('BASEDIR',dirname(dirname(dirname(__DIR__))));

require_once BASEDIR.'/src/library/Ergo/classes/Ergo/ClassLoader.php';

$classLoader = new \Ergo\ClassLoader();
$classLoader->register()->includePaths(
    array(
        BASEDIR."/src/library/Ergo/classes",
        BASEDIR."/src/",
        "/usr/share/php"
    )
);
