<?php
namespace AgileGrapher\Model;
use \AgileGrapher\Model\Model as ModelInterface;

/**
 * An Abstract model that implements the Model Interface
 * @category AgileGrapher
 * @package AgileGrapher_AbstractModel
 * @see AgileGrapher_Model
 */
abstract class AbstractModel implements ModelInterface
{    
    /**
     * Model Constructor
     * @param $values array key/value array
     */
    public function __construct(array $values) {

        if (method_exists($this, 'setCreated')) {
            $this->setCreated(date('Y-m-d H:i:s'));
        }
        $this->populate($values);
    }

    /**
     * Populate the Model with the provided key=>value pair array
     * @param array $values
     * @return \AgileGrapher\Model\AbstractModel fluent interface
     */
    public function populate(array $values) {
        foreach ($values as $propName => $propValue) {
            $methodName = 'set'.ucfirst($propName);
            if (method_exists($this, $methodName)) {
                $this->$methodName($propValue);
            }
        }
        return $this;
    }

    /**
     * Return the internal values as a key=>value pair array JSON encoded
     * @return string JSON encoded representation of this model
     */
    public function toJson() {
        return json_encode($this->toKeyValues());
    }

    /**
     * Determine whether this model is valid
     * @return bool
     */
    public function isValid() {
        return true;
    }
}
