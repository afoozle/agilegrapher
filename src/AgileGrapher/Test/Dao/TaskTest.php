<?php
namespace AgileGrapher\Test\Dao;
require_once __DIR__.'/../../Bootstrap.php';

use \AgileGrapher\Model\Task;
use \AgileGrapher\Dao\Task as TaskDao;
use \Mockery;

/**
 * @Author Matthew Wheeler <matt@yurisko.net>
 */
class TaskTest extends \PHPUnit_Framework_testCase
{
    /**
     * @var \AgileGrapher\Model\Task
     */
    protected $dummyTask;

    /**
     * Constructor
     */
    public function __construct() {
        $this->dummyTask = new Task(array(
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
     * Check that we return null when there are no results
     */
    public function testFindByIdReturnsNullWhenNoResultsFound() {
        $mockedEM = \Mockery::mock('\Doctrine\Orm\EntityManager', function($mock) {
            $mock->shouldReceive('find')->with('\AgileGrapher\Model\Task', 99)->once()->andReturn(null);
        });

        $taskDao = new TaskDao( $mockedEM );
        $this->assertEquals(null, $taskDao->findById(99));
    }

    /**
     * Check that we get an exception when multiple results are returned
     */
    public function testFindByIdThrowsExceptionWhenMultipleResultsMatch() {

        $mockedEM = \Mockery::mock('\Doctrine\Orm\EntityManager', function($mock) {
            $mock->shouldReceive('find')->with('\AgileGrapher\Model\Task', 99)->once()
                ->andThrow(new \Doctrine\ORM\NonUniqueResultException("foo"));
        });

        $taskDao = new TaskDao( $mockedEM );
        // @codingStandardsIgnoreStart
        try {
            $taskDao->findById(99);
            $this->fail("An expected Exception was not raised");
        }
        catch (\Doctrine\ORM\NonUniqueResultException $e) {
            // Success, DAO should have thrown this exception
        }
        // @codingStandardsIgnoreEnd
    }

    /**
     * Check that we get a single Task object when we match one result
     */
    public function testFindByIdReturnsModel() {
        $dummyTask = $this->dummyTask;
        $mockedEM = \Mockery::mock('\Doctrine\Orm\EntityManager', function($mock) use($dummyTask) {
            $mock->shouldReceive('find')->with('\AgileGrapher\Model\Task', 99)->once()->andReturn($dummyTask);
        });

        $taskDao = new TaskDao( $mockedEM );
        $this->assertEquals($this->dummyTask, $taskDao->findById(99));
    }

    public function testSaveFIXME() {
        $this->markTestSkipped("Not Implemented Yet");
    }

    //    public function testSaveAndFindByIdPersistsAndReturnsModel() {
    //        $task = new Task(array(
    //            'name'=>'Dummy Task',
    //            'description'=>'A dummy task',
    //        ));
    //
    //        $this->taskDao->save($task);
    //        $this->assertNotEmpty($task->getId());
    //
    //        $loadedTask = $this->taskDao->findById($task->getId());
    //        $this->assertEquals('Dummy Task', $loadedTask->getName());
    //        $this->assertEquals('A dummy task', $loadedTask->getDescription());
    //    }
    //
    //    public function testDelete() {
    //        $task = new Task(array(
    //            'name'=>'Dummy Task',
    //            'description'=>'A dummy task',
    //        ));
    //
    //        $this->taskDao->save($task);
    //        $taskId = $task->getId();
    //        $this->taskDao->delete($task);
    //
    //        $this->assertEquals(null, $this->taskDao->findById($taskId));
    //    }

    /**
     * Get Mocks required to support findById
     *
     * @param $returnValue The value we want the query to return
     * @return \Doctrine\Orm\EntityManager
     */
    protected function getMockForFindById($returnValue) {

        $mockedQuery = \Mockery::mock('\Doctrine\Orm\Query', function($mock) use($returnValue) {
            $mock->shouldReceive('setParameter')->with(1, 99)->once()
                ->shouldReceive('getResult')->once()->andReturn($returnValue);
        });

        $mockedEM = \Mockery::mock('\Doctrine\Orm\EntityManager', function($mock) use ($mockedQuery) {
            $mock->shouldReceive("createQuery")
                ->with("SELECT t from \AgileGrapher\Model\Task t where t.id = ?1")->once()
                ->andReturn($mockedQuery);
        });

        return $mockedEM;
    }
}
