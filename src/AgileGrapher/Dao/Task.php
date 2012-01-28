<?php
namespace AgileGrapher\Dao;
use \AgileGrapher\Model\Task as TaskModel;
use \AgileGrapher\Model\Model as ModelAbstract;

/**
 * Data Access Object for manipulating and retrieving Tasks
 */
class Task extends AbstractDao
{
    protected $_entityClass = '\AgileGrapher\Model\Task';
}
