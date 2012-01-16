<?php
namespace AgileGrapher\Dao;
use \AgileGrapher\Model\Task as TaskModel;
use \AgileGrapher\Model\Model as ModelAbstract;

/**
 * Data Access Object for manipulating and retrieving Tasks
 */
class Task extends AbstractDao
{
    /**
     * @param $id
     * @return \AgileGrapher\Model\Task
     */
    public function findById($id) {
        $query = $this->entityManager->createQuery("SELECT t from \AgileGrapher\Model\Task t where t.id = ?1");
        $query->setParameter(1, $id);

        $results = $query->getResult();
        $numResults = count($results);
        if ( $numResults == 0 ) {
            return null;
        }
        else if ( $numResults > 1 ) {
            throw new \Exception("Too many results returned, expected 1, got $numResults");
        }
        else {
            return array_shift($results);
        }
    }
}