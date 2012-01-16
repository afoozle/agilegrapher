<?php
require_once __DIR__.'/../src/AgileGrapher/Bootstrap.php';

use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;
use \Silex\Application;
use \Doctrine\ORM\EntityManager;
use \AgileGrapher\Model\Task;
use \AgileGrapher\Dao\Task as TaskDao;
use \Silex\Provider\TwigServiceProvider;

global $BOOTSTRAP;
$entityManager = $BOOTSTRAP->getEntityManager();

$app = new Application();
$app['debug'] = true;

$app->register(new TwigServiceProvider(), array(
    'twig.path' => BASEDIR.'/src/views',
    'twig.class_path' => BASEDIR.'/src/library/Silex/vendor/twig/lib'
));

/**
 * Load a task given it's id
 * @param int $taskId The Task to Load
 * @return \AgileGrapher\Model\Task
 */
$taskLoader = function($taskId) use($app, $entityManager) {
    $taskId = (int) $taskId;
    $taskDao = new TaskDao($entityManager);
    $task = $taskDao->findById($taskId);
    if ($task == null) {
        $app->abort(404, "Task ".$app->escape($taskId)." does not exist");
    }
    return $task;
};

// Home Page
$app->match('/',function() use($app) {
    return $app['twig']->render('home.html.twig',array());
})
->method('GET|POST');

// Retreive all tasks
$app->get('/tasks', function() use($app, $entityManager) {
   $app->abort(500, "Route not implemented yet");
});

// Retrieve a specific task
$app->get('/task/{task}', function(Task $task) use($app, $entityManager) {
    return new Response($task->toJson(), 200, array('Content-Type' => 'application/json'));
})
->convert('task', $taskLoader);

// Create a new task
$app->post('/task', function(Request $request) use($app, $entityManager) {
    if (!$data = $request->get('task')) {
        return new Response('Missing parameters.', 400);
    }

    $task = new Task($data);
    $taskDao = new TaskDao($entityManager);
    $taskDao->save($task);
    return $app->redirect('/task/'.$task->getId(), 201);
});

// Update Task
$app->put('/task/{task}', function(Task $task, Request $request) use($app, $entityManager) {
    if (!$data = $request->get('task')) {
        return new Response('Missing parameters.', 400);
    }

    $taskDao = new TaskDao($entityManager);
    $task->populate($data);
    $taskDao->save($task);
    return $app->redirect('/task/'.$task->getId(), 201);
})
->convert('task', $taskLoader);

// Delete the Task
$app->delete('/task/{task}', function(Task $task) use ($app, $entityManager) {
    $taskDao = new TaskDao($entityManager);
    $taskDao->delete($task);
    return new Response(200, "Task Deleted");
})
->convert('task', $taskLoader);

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