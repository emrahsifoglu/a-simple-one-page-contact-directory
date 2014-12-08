<?php

namespace ContactDirectory\UserBundle\Repository;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class UserRepository extends EntityRepository implements UserProviderInterface {

    public function loadUserByUsername($username){
        $q = $this
            ->createQueryBuilder('u')
            ->select('u, r')
            ->leftJoin('u.roles', 'r')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery();
        try {
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find an active admin User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }
        return $user;
    }

    public function loadUserByRoleName($roleName){
        $q = $this
            ->createQueryBuilder('u')
            ->select('u.email, u.username, r.name')
            ->leftJoin('u.roles', 'r')
            ->where('r.name = :roleName')
            ->setParameter('roleName', $roleName)
            ->getQuery();
        try {
            $users = $q->getArrayResult();
        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find an active admin User object identified by "%s".',
                $roleName
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }
        return $users;
    }

    public function refreshUser(UserInterface $user){
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }
        return $this->find($user->getId());
    }

    public function supportsClass($class){
        return $this->getEntityName() === $class
        || is_subclass_of($class, $this->getEntityName());
    }

}