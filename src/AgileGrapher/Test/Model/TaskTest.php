<?php
namespace AgileGrapher\Test\Model;
require_once __DIR__.'/../Bootstrap.php';

use \AgileGrapher\Model\Task;

class TaskTest extends \PHPUnit_Framework_testCase
{   
    public function testConstructSetsValues() {
        
        $taskValues = array(
            'id' => 99,
            'name' => 'TestName',
            'description' => 'TestDescription',
            'created' => '2001-01-01 12:34:56',
            'completed' => '2002-02-02 01:23:45'
        );
        
        $task = new Task($taskValues);
        $this->assertEquals(99, $task->getId());
        $this->assertEquals('TestName', $task->getName());
        $this->assertEquals('TestDescription', $task->getDescription());
        $this->assertEquals('2001-01-01 12:34:56', $task->GetCreated());
        $this->assertEquals('2002-02-02 01:23:45', $task->getCompleted());
    }

    public function testSetGetName() {
        $task = new Task(array());
        $task->setName('testName');
        $this->assertEquals('testName', $task->getName());
    }

    public function testFooDoctrine(){

        $config = new \Doctrine\ORM\Configuration();
        $config->setProxyDir(BASEDIR.'/src/AgileGrapher/Data/Proxy');
        $config->setProxyNamespace('AgileGrapher\Data\Proxy');
        $config->setAutoGenerateProxyClasses(true); // FIXME, Dev Only

        $driver = $config->newDefaultAnnotationDriver(BASEDIR.'/src/AgileGrapher/Model');
        $config->setMetadataDriverImpl($driver);

        $cache = new \Doctrine\Common\Cache\ArrayCache();
        $config->setMetaDataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        $dbConnection = array(
            'driver' => 'pdo_sqlite',
            'path' => BASEDIR.'/db/agilegrapher.db'
        );
        $eventManager = new \Doctrine\Common\EventManager();
        $entityManager = \Doctrine\ORM\EntityManager::create($dbConnection, $config, $eventManager);

        $task = new Task(array(
            'name'=>'Dummy Task',
            'description'=>'A dummy task',
        ));

        $entityManager->persist($task);
        $entityManager->flush();
    }
}
