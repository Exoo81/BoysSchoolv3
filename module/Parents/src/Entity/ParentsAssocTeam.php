<?php
namespace Parents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ourteam\Entity\OurTeam;

/**
 * This class represents a LearningSupport.
 * @ORM\Entity()
 * @ORM\Table(name="our_team")
 */
class ParentsAssocTeam extends OurTeam {
    
    
    /** 
     * @ORM\Column(name="parents_assoc_role")  
     */
    protected $parentsAssocRole;
    
    
    
    /**
     * Returns role.
     * @return string     
     */
    public function getParentsAssocRole() {
        return $this->parentsAssocRole;
    } 
    
    /**
     * Returns Role string.
     * @return string     
     */
    public function getParentsAssocRoleAsString() {
        $roleString = '';
        if($this->parentsAssocRole == 1){
            $roleString = 'Chairperson';
        }
        if($this->parentsAssocRole == 2){
            $roleString = 'Vice Chairperson';
        }
        if($this->parentsAssocRole == 3){
            $roleString = 'Secretary';
        }
        if($this->parentsAssocRole == 4){
            $roleString = 'Treasurer';
        }
        return $roleString;
    }

    /**
     * Sets $parentsAssocRole.
     * @param string $parentsAssocRole
     */
    public function setParentsAssocRole($parentsAssocRole) {
        $this->parentsAssocRole = $parentsAssocRole;
    }
    
    
}

