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
    protected $entityManager;

    /**
     * @var string The class name this model relates to
     */
    protected $entityClass;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \AgileGrapher\Model\Model $model
     */
    public function save(ModelAbstract $model) {
        $this->entityManager->persist($model);
        $this->entityManager->flush();
    }

    /**
     * @param \AgileGrapher\Model\Model $model
     */
    public function delete(ModelAbstract $model) {
        $this->entityManager->remove($model);
        $this->entityManager->flush();
    }


    /**
     * Find a single result using Primary Key
     *
     * @param $id
     * @return \AgileGrapher\Model\Task
     */
    public function findById($id) {
        return $this->entityManager->find($this->entityClass, $id);
    }

    /**
     * Find all results
     *
     * @return mixed
     */
    public function findAll() {
        return $this->entityManager->getRepository($this->entityClass)->findAll();
    }

}
