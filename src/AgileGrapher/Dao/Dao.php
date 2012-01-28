<?php
namespace AgileGrapher\Dao;

/**
 * Interface methods for Data Access Objects
 */
interface Dao
{
    public function findById($id);

    public function findAll();

    public function save(\AgileGrapher\Model\Model $model);

    public function delete(\AgileGrapher\Model\Model $model);
}