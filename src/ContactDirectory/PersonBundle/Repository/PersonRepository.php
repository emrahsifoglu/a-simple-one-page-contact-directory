<?php
namespace ContactDirectory\PersonBundle\Repository;

use ContactDirectory\PersonBundle\Entity\Person;
use ContactDirectory\PersonBundle\Model\PersonModel;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class PersonRepository extends EntityRepository {

    public function fetch($id){
        $q = $this
            ->createQueryBuilder('p')
            ->select('p.id, p.firstName, p.lastName')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        try {
            $person = $q->getSingleResult();
        } catch (NoResultException $e) {
            $person = new PersonModel();
            $person->setId(0);
        }
        return $person;
    }

    public function fetchAll(){
        $q = $this
            ->createQueryBuilder('p')
            ->select('p.id, p.firstName, p.lastName')
            ->getQuery();

        return $q->getResult();
    }

    public function update($id, $firstName, $lastName){
        $q = $this->createQueryBuilder('p')
            ->update()
            ->set('p.firstName', ':firstName')
            ->set('p.lastName', ':lastName')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->setParameter('firstName', $firstName)
            ->setParameter('lastName', $lastName)
            ->getQuery();
        return $q->execute();
    }

    public function delete($id){
        $q = $this->createQueryBuilder('p')
            ->delete()
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
        return $q->execute();
    }

    public function supportsClass($class){
        return $this->getEntityName() === $class
        || is_subclass_of($class, $this->getEntityName());
    }
}
