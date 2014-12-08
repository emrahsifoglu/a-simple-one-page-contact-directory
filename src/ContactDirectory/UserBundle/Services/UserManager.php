<?php
namespace ContactDirectory\UserBundle\Services;

use Doctrine\ORM\EntityManager;
use ContactDirectory\UserBundle\Entity\User;

class UserManager {

    /**
     * @var EntityManager $em
     */
    protected $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    /**
     * @param string $username
     * @return User
     */
    public function findByRoleName($username){
        return $this->em->getRepository('ContactDirectoryUserBundle:User')->loadUserByUsername($username);
    }

} 