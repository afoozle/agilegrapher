<?php
/**
 * @author Matthew Wheeler <matt@yurisko.net>
 * @copyright Copyright Matthew Wheeler 2012
 * @category AgileGrapher
 * @package AgileGrapher_Model
 */
namespace AgileGrapher\Model;

/**
 * Interface Contract for Model classes
 *
 * @category AgileGrapher
 * @package AgileGrapher_Model
 */
interface Model
{
    /**
     * Serialize this object to JSON
     * @abstract
     * @return string Json Encoded String
     */
    public function toJson();

    /**
     * Return as key->value pair
     * @abstract
     * @return array key->value pair array
     */
    public function toKeyValues();

    /**
     * Update existing values in a model with new ones
     * @abstract
     * @param array $values
     */
    public function populate(array $values);


    /**
     * Check whether a model is valid
     * @abstract
     * @return bool whether the model values are "valid" or not
     */
    public function isValid();
}
