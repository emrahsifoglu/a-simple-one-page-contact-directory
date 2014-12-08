<?php
namespace ContactDirectory\UserBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use ContactDirectory\UserBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null){
        var_dump('getting container here');
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager){
        $admin = new User();
        $admin->setFirstName("admin");
        $admin->setLastName("name ");
        $admin->setUsername("admin");
        $admin->setSalt(md5(uniqid()));
        $encoder = new MessageDigestPasswordEncoder('sha1', false, 1);
        $admin->setPassword($encoder->encodePassword('blue', $admin->getSalt()));
        $admin->setEmail("admin@mail.com");
        $admin->setIsActive(true);
        $manager->persist($admin);
        $manager->flush();
    }
}