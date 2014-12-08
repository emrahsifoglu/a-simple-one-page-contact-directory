<?php
namespace ContactDirectory\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ContactDirectory\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @DoctrineAssert\UniqueEntity(fields={"username"}, message="username.already.exist" )
 * @DoctrineAssert\UniqueEntity(fields={"email"}, message="email.already.exist" )
 *
 */

class User implements UserInterface, AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, name="first_name")
     * @var string $firstName
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=50, name="last_name")
     * @var string $lastName
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=100,unique=true)
     * @var string $email
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=15, unique=true)
     * @var string $username
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string $password
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=32)
     * @var string $salt
     */
    private $salt;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @var boolean $is_active
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @var ArrayCollection
     */
    private $roles;

    public function __construct(){
        $this->roles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName){
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName(){
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName){
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName(){
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email){
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username){
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername(){
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password){
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword(){
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt){
        $this->salt = $salt;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt(){
        return $this->salt;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive){
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive(){
        return $this->isActive;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(){
        return $this->roles->toArray();
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(){
    }

    public function isAccountNonExpired(){
        return true;
    }

    public function isAccountNonLocked(){
        return true;
    }

    public function isCredentialsNonExpired(){
        return true;
    }

    public function isEnabled(){
        return $this->isActive;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize(){
        return serialize(array(
                $this->id,
                $this->username,
                $this->password
            ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized){
        list (
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized);
    }

    /**
     * Add roles
     *
     * @param \ContactDirectory\UserBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(Role $roles){
        $this->roles[] = $roles;
        return $this;
    }

    /**
     * Remove roles
     *
     * @param \ContactDirectory\UserBundle\Entity\Role $roles
     */
    public function removeRole(Role $roles){
        $this->roles->removeElement($roles);
    }
}
