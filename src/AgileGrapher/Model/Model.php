<?php
namespace AgileGrapher\Model;

interface Model
{
    /**
     * Serialize this object to JSON
     * @abstract
     * @return string Json Encoded String
     */
    public function toJson();

    /**
     * Update existing values in a model with new ones
     * @abstract
     * @param array $values
     */
    public function populate(array $values);
}
