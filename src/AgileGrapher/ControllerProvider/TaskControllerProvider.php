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
            $taskDao = $app['taskDao'];
            $task = $taskDao->findById($taskId);
            if ($task == null) {
                $app->abort(404, "Task ".$app->escape($taskId)." does not exist");
            }
            return $task;
        };

        $controllers->get('/all', function() use($app) {
            $taskDao = $app['taskDao'];
            $tasks = $taskDao->findAll();

            $returnValues = array();
            foreach($tasks as $task) {
                $returnValues[] = $task->toKeyValues();
            }
            return new Response(json_encode($returnValues), 200, array('Content-Type' => 'application/json'));
        });

        // Retrieve a specific task
        $controllers->get('/{task}', function(Task $task) use($app) {
            return new Response($task->toJson(), 200, array('Content-Type' => 'application/json'));
        })
        ->convert('task', $taskLoader);

        // Create a new task and return it's new values
        $controllers->post('/', function(Request $request) use($app) {
            $data = json_decode($request->request->get("task"), true);
            if ($data == null) {
                return new Response('Missing parameters.', 400);
            }

            $task = new Task($data);
            $taskDao = $app['taskDao'];
            $taskDao->save($task);
            return new Response($task->toJson(), 200, array('Content-Type' => 'application/json'));
        });

        // Update Task
        $controllers->put('/{task}', function(Task $task, Request $request) use($app) {
            $data = json_decode($request->request->get("task"), true);
            if ($data == null) {
                return new Response('Missing parameters.', 400);
            }

            $taskDao = $app['taskDao'];
            $task->populate($data);
            $taskDao->save($task);
            return new Response($task->toJson(), 200, array('Content-Type' => 'application/json'));
        })
        ->convert('task', $taskLoader);

        // Delete the Task
        $controllers->delete('/{task}', function(Task $task) use ($app) {
            $taskDao = $app['taskDao'];
            $taskDao->delete($task);
            return new Response("Task Deleted", 200);
        })
        ->convert('task', $taskLoader);

        return $controllers;
    }
}