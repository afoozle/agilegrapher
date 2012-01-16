<?php
namespace AgileGrapher\Test\Dao;
require_once __DIR__.'/../../Bootstrap.php';

use \AgileGrapher\Model\Task;
use \AgileGrapher\Dao\Task as TaskDao;

/**
 * @Author Matthew Wheeler <matt@yurisko.net>
 */
class TaskTest extends \PHPUnit_Framework_testCase
{
    /**
     * @Var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \AgileGrapher\Dao\Task
     */
    protected $taskDao;

    public function __construct() {
        global $BOOTSTRAP;
        $this->entityManager = $BOOTSTRAP->getEntityManager();
        $this->taskDao = new TaskDao($this->entityManager);
    }

    public function testFindByIdWithInvalidIdReturnsNull() {
        $task = $this->taskDao->findById(-99);
        $this->assertEquals(null, $task);
    }

    public function testSaveAndFindByIdPersistsAndReturnsModel() {
        $task = new Task(array(
            'name'=>'Dummy Task',
            'description'=>'A dummy task',
        ));

        $this->taskDao->save($task);
        $this->assertNotEmpty($task->getId());

        $loadedTask = $this->taskDao->findById($task->getId());
        $this->assertEquals('Dummy Task', $loadedTask->getName());
        $this->assertEquals('A dummy task', $loadedTask->getDescription());
    }

    public function testDelete() {
        $task = new Task(array(
            'name'=>'Dummy Task',
            'description'=>'A dummy task',
        ));

        $this->taskDao->save($task);
        $taskId = $task->getId();
        $this->taskDao->delete($task);

        $this->assertEquals(null, $this->taskDao->findById($taskId));
    }
}
