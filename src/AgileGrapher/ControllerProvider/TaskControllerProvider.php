<?php
namespace AgileGrapher\ControllerProvider;

use Silex\Application,
    Silex\ControllerProviderInterface,
    Silex\ControllerCollection,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\Request,
    AgileGrapher\Model\Task,
    AgileGrapher\Dao\Task as TaskDao;

class TaskControllerProvider implements ControllerProviderInterface
{
    /**
     * Connect routes to their handling actions
     *
     * @param \Silex\Application $app
     * @return \Silex\ControllerCollection
     */
    public function connect( Application $app ) {
        $controllers = new ControllerCollection();

        $taskLoader = function($task) use($app) {
            return TaskControllerProvider::taskLoader($app, $task);
        };

        $taskDao = $app['taskDao'];

        $controllers->get( '/all', function( Request $request ) use( $taskDao ) {
            return TaskControllerProvider::listAction($request, $taskDao);
        });

        $controllers->get('/{task}', function( Request $request, Task $task ) use( $taskDao ) {
            return TaskControllerProvider::getAction($request, $taskDao, $task);
        })->convert( 'task', $taskLoader );

        $controllers->post( '/', function( Request $request ) use( $taskDao ) {
            return TaskControllerProvider::addAction($request, $taskDao);
        });

        $controllers->put( '/{task}', function( Request $request, Task $task ) use( $taskDao ) {
            return TaskControllerProvider::updateAction($request, $taskDao, $task);
        })->convert( 'task', $taskLoader );

        $controllers->delete( '/{task}', function( Request $request, Task $task ) use ( $taskDao ) {
            return TaskControllerProvider::deleteAction($request, $taskDao, $task);
        })->convert( 'task', $taskLoader );

        return $controllers;
    }

    /**
     * Retrieve a specific task and return it JSON encoded
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AgileGrapher\Dao\Task                    $taskDao
     * @param \AgileGrapher\Model\Task                  $task
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function getAction(Request $request, TaskDao $taskDao, Task $task) {
        return new Response( $task->toJson(), 200, array( 'Content-Type' => 'application/json' ) );
    }

    /**
     * Update an existing task and return the updated record JSON encoded
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AgileGrapher\Dao\Task                    $taskDao
     * @param \AgileGrapher\Model\Task                  $task
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function updateAction(Request $request, TaskDao $taskDao, Task $task) {
        $data = json_decode( $request->request->get( "task" ), true );
        if ( $data == null ) {
            return new Response( 'Missing parameters', 400 );
        }

        $task->populate( $data );
        $taskDao->save( $task );
        return new Response( $task->toJson(), 200, array( 'Content-Type' => 'application/json' ) );
    }

    /**
     * Add a new task and return it as a JSON encoded record
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AgileGrapher\Dao\Task                    $taskDao
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function addAction(Request $request, TaskDao $taskDao) {
        $data = json_decode( $request->request->get( "task" ), true );
        if ( $data == null ) {
            return new Response( 'Missing parameters', 400 );
        }

        $task = new Task( $data );
        $taskDao->save( $task );
        return new Response( $task->toJson(), 200, array( 'Content-Type' => 'application/json' ) );
    }

    /**
     * Return a JSON encoded array of tasks
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AgileGrapher\Dao\Task                    $taskDao
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function listAction(Request $request, TaskDao $taskDao) {
        $tasks = $taskDao->findAll();

        $returnValues = array();
        foreach ( $tasks as $task ) {
            $returnValues[] = $task->toKeyValues();
        }
        return new Response( json_encode( $returnValues ), 200, array( 'Content-Type' => 'application/json' ) );
    }

    /**
     * Delete a task
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AgileGrapher\Dao\Task                    $taskDao
     * @param \AgileGrapher\Model\Task                  $task
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function deleteAction(Request $request, TaskDao $taskDao, Task $task) {
        $taskDao->delete($task);
        return new Response("Task deleted", 200);
    }

    /**
     * Load a task given it's id
     * @param int $taskId The Task to Load
     * @param \Silex\Application   $app
     * @return \AgileGrapher\Model\Task
     */
    public static function taskLoader( Application $app, $taskId ) {

        $taskId = (int)$taskId;
        $taskDao = $app['taskDao'];
        $task = $taskDao->findById( $taskId );
        if ( $task == null ) {
            $app->abort( 404, "Task " . $app->escape( $taskId ) . " does not exist" );
        }
        return $task;
    }
}
