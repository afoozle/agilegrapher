<?php
namespace AgileGrapher\Dao;
use \Doctrine\ORM\EntityManager,
    \AgileGrapher\Model\Model as ModelAbstract,
    \AgileGrapher\Dao\Dao as DaoInterface;
/**
 * Base Data Access class
 * @author Matthew Wheeler <matt@yurisko.net>
 */
abstract class AbstractDao implements DaoInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_entityManager;

    /**
     * @var string The class name this model relates to
     */
    protected $_entityClass;

    public function __construct(EntityManager $entityManager) {
        $this->_entityManager = $entityManager;
    }

    /**
     * @param \AgileGrapher\Model\Model $model
     */
    public function save(ModelAbstract $model) {
        $this->_entityManager->persist($model);
        $this->_entityManager->flush();
    }

    /**
     * @param \AgileGrapher\Model\Model $model
     */
    public function delete(ModelAbstract $model) {
        $this->_entityManager->remove($model);
        $this->_entityManager->flush();
    }


    /**
     * Find a single result using Primary Key
     *
     * @param $id
     * @return \AgileGrapher\Model\Task
     */
    public function findById($id) {
        return $this->_entityManager->find($this->_entityClass, $id);
    }

    /**
     * Find all results
     *
     * @return mixed
     */
    public function findAll() {
        return $this->_entityManager->getRepository($this->_entityClass)->findAll();
    }

}
