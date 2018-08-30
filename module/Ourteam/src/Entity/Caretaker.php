<?php

namespace Ourteam\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Ourteam\Entity\OurTeam;

/**
 * This class represents a Caretaker.
 * @ORM\Entity()
 * @ORM\Table(name="our_team")
 */
class Caretaker extends OurTeam {
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId()
        ];
    }
    
}

