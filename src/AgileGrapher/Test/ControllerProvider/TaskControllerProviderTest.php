<?php
namespace AgileGrapher\Test\ControllerProvider;
require_once __DIR__.'/../../Bootstrap.php';

use \AgileGrapher\Model\Task,
    \AgileGrapher\Dao\Task as TaskDao,
    \AgileGrapher\ControllerProvider\TaskControllerProvider,
    \Symfony\Component\HttpFoundation\Response,
    \Symfony\Component\HttpFoundation\Request,
    \Mockery;


class TaskControllerProviderTest extends \PHPUnit_Framework_TestCase
{
    protected $_dummyTask;

    public function __construct() {
        $this->_dummyTask = new Task(array(
            'id' => 99,
            'name' => 'TestName',
            'description' => 'TestDescription',
            'created' => '2001-01-01 12:34:56',
            'completed' => '2002-02-02 01:23:45'
        ));
    }

    /**
     * Teardown the mocking infrastructure
     */
    public function tearDown() {
        \Mockery::close();
    }

    /**
     * Test that we have the correct routes wired up
     */
    public function testConnect() {

        $application = new \Silex\Application();
        $application['taskDao'] = \Mockery::mock('\AgileGrapher\Dao\Task');
        $controllerProvider = new TaskControllerProvider();
        $routes = $controllerProvider->connect($application)->flush();

        $this->assertNotNull($routes->get('GET_all'));
        $this->assertNotNull($routes->get('GET_task'));
        $this->assertNotNull($routes->get('PUT_task'));
        $this->assertNotNull($routes->get('POST_'));
        $this->assertNotNull($routes->get('DELETE_task'));
    }


    /**
     * Test that we get a JSON encoded task
     */
    public function testGetAction() {
        $mockedDao = \Mockery::mock('\AgileGrapher\Dao\Task');

        $response = TaskControllerProvider::getAction(new Request(), $mockedDao, $this->_dummyTask);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->_dummyTask->toJson(), $response->getContent());
    }

    /**
     * Check that we get status code 400 when task data is missing
     */
    public function testUpdateActionReturnsStatus400() {
        $mockedDao = \Mockery::mock('\AgileGrapher\Dao\Task');

        $request = new Request();
        $request->request->set('foo', 'bar');

        $response = TaskControllerProvider::updateAction($request, $mockedDao, $this->_dummyTask);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Missing parameters', $response->getContent());
    }

    /**
     * Test that we can update an existing action
     */
    public function testUpdateAction() {

        $preUpdate = clone $this->_dummyTask;
        $postUpdate = clone $this->_dummyTask;
        $postUpdate->setName('updated name');
        $postUpdate->setDescription('updated description');

        $mockedDao = \Mockery::mock('\AgileGrapher\Dao\Task', function($mock) {
            $mock->shouldReceive('save')->with('\AgileGrapher\Model\Task')->once();
        });

        $request = new Request();
        $request->request->set('task', $postUpdate->toJson());

        $response = TaskControllerProvider::updateAction($request, $mockedDao, $preUpdate);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals($postUpdate->toJson(), $response->getContent());
    }

    /**
     * Check that we get status code 400 when missing request data
     */
    public function testAddActionReturnsStatus400() {
        $mockedDao = \Mockery::mock('\AgileGrapher\Dao\Task');

        $request = new Request();
        $request->request->set('foo', 'bar');

        $response = TaskControllerProvider::addAction($request, $mockedDao);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Missing parameters', $response->getContent());
    }

    /**
     * Test that we can add new Records
     */
    public function testAddAction() {

        $dummyTask = $this->_dummyTask;
        $dummyTask->setId(null);

        $mockedDao = \Mockery::mock('\AgileGrapher\Dao\Task', function($mock) {
           $mock->shouldReceive('save')->with('\AgileGrapher\Model\Task')->once();
        });

        $request = new Request();
        $request->request->set('task', $dummyTask->toJson());

        $response = TaskControllerProvider::addAction($request, $mockedDao);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals($dummyTask->toJson(), $response->getContent());
    }

    /**
     * Test that we can get a list of Tasks
     */
    public function testListAction() {

        $taskCollection = array(
            new Task( array('name'=>'task1','description'=>'description1') ),
            new Task( array('name'=>'task2','description'=>'description2') ),
            new Task( array('name'=>'task3','description'=>'description3') ),
            new Task( array('name'=>'task4','description'=>'description4') ),
            new Task( array('name'=>'task5','description'=>'description5') )
        );

        $taskCollectionEncoded = json_encode(array(
            $taskCollection[0]->toKeyValues(),
            $taskCollection[1]->toKeyValues(),
            $taskCollection[2]->toKeyValues(),
            $taskCollection[3]->toKeyValues(),
            $taskCollection[4]->toKeyValues()
        ));


        $mockedDao = \Mockery::mock('\AgileGrapher\Dao\Task', function($mock) use ($taskCollection) {
            $mock->shouldReceive('findAll')->once()
                ->andReturn($taskCollection);
        });

        $response = TaskControllerProvider::listAction(new Request(), $mockedDao);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals($taskCollectionEncoded, $response->getContent());
    }

    /**
     * Test that we can delete a task
     */
    public function testDeleteAction() {

        $mockedDao = \Mockery::mock('\AgileGrapher\Dao\Task', function($mock) {
            $mock->shouldReceive('delete')->with('\AgileGrapher\Model\Task')->once();
        });

        $response = TaskControllerProvider::deleteAction(new Request(), $mockedDao, $this->_dummyTask);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Task deleted', $response->getContent());
    }
}