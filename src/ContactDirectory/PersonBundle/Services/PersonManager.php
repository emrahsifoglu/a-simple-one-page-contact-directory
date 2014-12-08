<?php

namespace ContactDirectory\PersonBundle\Services;

use ContactDirectory\PersonBundle\Entity\Person;
use Doctrine\ORM\EntityManager;

class PersonManager {

    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    public function create($firstName, $lastName){
        $person = new Person();
        $person->setFirstName($firstName);
        $person->setLastName($lastName);
        $this->em->persist($person);
        $this->em->flush();
        return $person->getId();
    }

    /**
     * @param int $id
     * @return Person
     */
    public function fetch($id){
        return $this->em->getRepository('ContactDirectoryPersonBundle:Person')->fetch($id);
    }

    /**
     * @return Array
     */
    public function fetchAll(){
        return $this->em->getRepository('ContactDirectoryPersonBundle:Person')->fetchAll();
    }

    public function update($id, $firstName, $lastName){
        return $this->em->getRepository('ContactDirectoryPersonBundle:Person')->update($id, $firstName, $lastName);
    }

    public function delete($id){
        /*$person = $this->fetch($id);
        $this->em->remove($person);
        $this->em->flush();*/

        return  $this->em->getRepository('ContactDirectoryPersonBundle:Person')->delete($id);
    }

}