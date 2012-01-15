<?php
namespace AgileGrapher\Model;

class Task extends AbstractModel implements Model 
{
    protected $id;
    protected $name;
    protected $description;
    protected $created;
    protected $completed;
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
    
    public function getCreated() {
        return $this->created;
    }
    
    public function setCreated($created) {
        $this->created = $created;
    }
    
    public function getCompleted() {
        return $this->completed;
    }
    
    public function setCompleted($completed) {
        $this->completed = $completed;
    }
}
