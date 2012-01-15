<?php
namespace AgileGrapher\Test;

require_once __DIR__.'/../../library/Silex/silex.phar';
use Silex\Application;

Bootstrap::bootstrap();

class Bootstrap
{
    public static function bootstrap() {
        self::initAutoloader();
    }
    
    public static function initAutoloader() {
        $app = new Application();
        $app['autoloader']->registerNamespace('AgileGrapher', __DIR__.'/../../');
    }
}
