<?php
namespace AgileGrapher\Test\Model;
require_once __DIR__.'/../../Bootstrap.php';

use \AgileGrapher\Model\Task;

class TaskTest extends \PHPUnit_Framework_testCase
{
    protected $testValues = array(
        'id' => 99,
        'name' => 'TestName',
        'description' => 'TestDescription',
        'created' => '2001-01-01 12:34:56',
        'completed' => '2002-02-02 01:23:45'
    );

    public function testConstructSetsValues() {
        $task = new Task($this->testValues);
        $this->assertEquals($this->testValues['id'], $task->getId());
        $this->assertEquals($this->testValues['name'], $task->getName());
        $this->assertEquals($this->testValues['description'], $task->getDescription());
        $this->assertEquals($this->testValues['created'], $task->GetCreated());
        $this->assertEquals($this->testValues['completed'], $task->getCompleted());
    }

    public function testSetGetProperties() {
        $task = new Task(array());
        $task->setName('testName');
        $this->assertEquals('testName', $task->getName());
        $task->setDescription('testDescription');
        $this->assertEquals('testDescription', $task->getDescription());
        $task->setCreated('2010-10-10 10:10:10');
        $this->assertEquals('2010-10-10 10:10:10', $task->getCreated());
        $task->setCompleted('2011-11-11 11:11:11');
        $this->assertEquals('2011-11-11 11:11:11', $task->getCompleted());
    }

    public function testToJson() {
        $task = new Task($this->testValues);
        $json = $task->toJson();
        $results = json_decode($json);

        $this->assertEquals($this->testValues['id'], $results->taskId);
        $this->assertEquals($this->testValues['name'], $results->name);
        $this->assertEquals($this->testValues['description'], $results->description);
        $this->assertEquals($this->testValues['created'], $results->created);
        $this->assertEquals($this->testValues['completed'], $results->completed);
    }

    public function testPopulateUpdatesValues() {
        $task = new Task($this->testValues);
        $task->populate(array(
            'name' => 'Updated Name',
            'description' => 'Updated Description',
            'created' => '2008-08-08 08:08:08',
            'completed' => '2009-09-09 09:09:09'
        ));

        $this->assertEquals('Updated Name', $task->getName());
        $this->assertEquals('Updated Description', $task->getDescription());
        $this->assertEquals('2008-08-08 08:08:08', $task->getCreated());
        $this->assertEquals('2009-09-09 09:09:09', $task->getCompleted());
    }
}
