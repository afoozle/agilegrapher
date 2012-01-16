<?php
namespace AgileGrapher\ControllerProvider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AgileGrapher\Model\Task;
use AgileGrapher\Dao\Task as TaskDao;

class TaskControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app) {
        $controllers = new ControllerCollection();

        /**
         * Load a task given it's id
         * @param int $taskId The Task to Load
         * @return \AgileGrapher\Model\Task
         */
        $taskLoader = function($taskId) use($app) {
            $taskId = (int) $taskId;
            $taskDao = new TaskDao($app['entityManager']);
            $task = $taskDao->findById($taskId);
            if ($task == null) {
                $app->abort(404, "Task ".$app->escape($taskId)." does not exist");
            }
            return $task;
        };

        // Retrieve a specific task
        $controllers->get('/{task}', function(Task $task) use($app) {
            return new Response($task->toJson(), 200, array('Content-Type' => 'application/json'));
        })
        ->convert('task', $taskLoader);

        // Create a new task
        $controllers->post('/', function(Request $request) use($app) {
            if (!$data = $request->get('task')) {
                return new Response('Missing parameters.', 400);
            }

            $task = new Task($data);
            $taskDao = new TaskDao($app['entityManager']);
            $taskDao->save($task);
            return $app->redirect('/task/'.$task->getId(), 201);
        });

        // Update Task
        $controllers->put('/{task}', function(Task $task, Request $request) use($app) {
            if (!$data = $request->get('task')) {
                return new Response('Missing parameters.', 400);
            }

            $taskDao = new TaskDao($app['entityManager']);
            $task->populate($data);
            $taskDao->save($task);
            return $app->redirect('/task/'.$task->getId(), 201);
        })
        ->convert('task', $taskLoader);

        // Delete the Task
        $controllers->delete('/{task}', function(Task $task) use ($app, $entityManager) {
            $taskDao = new TaskDao($entityManager);
            $taskDao->delete($task);
            return new Response(200, "Task Deleted");
        })
        ->convert('task', $taskLoader);

        return $controllers;
    }
}