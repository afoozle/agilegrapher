<?php
namespace AgileGrapher\Model;

/**
 * Model Class for Tasks
 *
 * @author Matthew Wheeler <matt@yurisko.net>
 * @Entity
 * @Table(name="task")
 */
class Task extends AbstractModel implements Model 
{
    /**
     * @Id
     * @Column(type="integer",name="task_id",unique="true")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @Column(type="string",name="name") */
    protected $name;

    /** @Column(type="string",name="description") */
    protected $description;

    /** @column(type="string",name="created") */
    protected $created;

    /** @column(type="string",name="completed",nullable=true) */
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

    public function setDescription( $description ) {
        $this->description = $description;
    }
    
    public function getCreated() {
        return $this->created;
    }
    
    public function setCreated( $created ) {
        $this->created = $created;
    }
    
    public function getCompleted() {
        return $this->completed;
    }
    
    public function setCompleted( $completed ) {
        $this->completed = $completed;
    }

    public function toKeyValues() {
        $values = array(
            'task_id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'created' => $this->getCreated(),
            'completed' => $this->getCompleted()
        );
        return $values;
    }
}
