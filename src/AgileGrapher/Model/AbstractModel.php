<?php
namespace AgileGrapher\Model;
use AgileGrapher\Model\ModelException;

abstract class AbstractModel implements Model 
{    
    /**
     * Model Constructor
     * @param $values array key/value array
     */
    public function __construct(array $values) {
    
        foreach ($values as $propName=>$propValue) {
            $methodName = 'set'.ucfirst($propName);
            if (method_exists($this, $methodName)) {
                $this->$methodName($propValue);
            }
            else {
                throw new ModelException("Model ".get_class($this)." does not have a setter for property ".$propName);
            }
        }
    }
    
}
