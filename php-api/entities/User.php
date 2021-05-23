<?php

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Assert\PositiveORZero
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(normalizer="trim", message="Username is required")
     * @Assert\Length(max = 25, maxMessage = "Username cannot be longer than {{ limit }} characters")
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(min = 8, minMessage = "Password must be at least {{ limit }} characters long")
     * @Assert\NotNull
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(normalizer="trim", message="First name is required")
     * @Assert\Length(max = 40,  maxMessage = "First name cannot be longer than {{ limit }} characters")
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=35)
     * @Assert\NotBlank(normalizer="trim", message="Last name is required")
     * @Assert\Length(max = 35,  maxMessage = "Last name cannot be longer than {{ limit }} characters")
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(normalizer="trim", message="Address is required")
     * @Assert\Length(max = 100,  maxMessage = "Address cannot be longer than {{ limit }} characters")
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Email(
     *     message = "'{{ value }}' is not a valid email address."
     * )
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Length(max = 15,  maxMessage = "Phone number cannot be longer than {{ limit }} characters")
     * @Assert\Regex(pattern="/(\d\D*){10}/", message="Phone number must contain at least 10 digits" )
     */
    protected $phone;


    /**
     * ================================================================
     *                      GETTERS AND SETTERS
     * ================================================================
     */


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }


}