<?php
namespace AgileGrapher\Dao;
use \Doctrine\ORM\EntityManager;
use \AgileGrapher\Model\Model as ModelAbstract;
/**
 * Base Data Access class
 * @author Matthew Wheeler <matt@yurisko.net>
 */
abstract class AbstractDao
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    abstract function findById($id);

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
        error_log("Delete called on AbstratDAO ".var_export($model, true));
        try {
            $this->entityManager->remove($model);
            $this->entityManager->flush();
        }
        catch (Exception $e) {
            error_log("Exception Caught: " . $e->getMessage());
        }

    }

}
