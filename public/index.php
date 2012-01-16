<?php
require_once __DIR__.'/../src/AgileGrapher/Bootstrap.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Doctrine\ORM\EntityManager;

use Silex\Provider\TwigServiceProvider;

global $BOOTSTRAP;

$app = new Application();
$app['debug'] = true;
$app['entityManager'] = $app->share(function() use($BOOTSTRAP) {
   return $BOOTSTRAP->getEntityManager();
});

$app->register(new TwigServiceProvider(), array(
    'twig.path' => BASEDIR.'/src/views',
    'twig.class_path' => BASEDIR.'/src/library/Silex/vendor/twig/lib'
));

$app->mount('/task', new AgileGrapher\ControllerProvider\TaskControllerProvider());

// Home Page
$app->match('/',function() use($app) {
    return $app['twig']->render('home.html.twig',array());
})
->method('GET|POST');

// Retreive all tasks
$app->get('/tasks', function() use($app) {
   $app->abort(500, "Route not implemented yet");
});



// Error Handler
$app->error(function (\Exception $e, $code) use($app) {
    if ($app['debug']) {
        return;
    }

    switch ($code) {
        case 400:
            $message = 'Bad Request';
        case 404:
            $message = 'Page Not Found';
            break;
        default:
            $message = 'Internal Server Error';
    }

    return new Response($message, $code);
});

$app->run();