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

        if (method_exists($this,'setCreated')) {
            $this->setCreated(date('Y-m-d H:i:s'));
        }
        $this->populate($values);
    }

    public function populate(array $values) {
        foreach ($values as $propName=>$propValue) {
            $methodName = 'set'.ucfirst($propName);
            if (method_exists($this, $methodName)) {
                $this->$methodName($propValue);
            }
        }
    }

    public function toJson() {
        return json_encode($this->toKeyValues());
    }
}
