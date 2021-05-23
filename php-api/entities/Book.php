<?php

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="books")
 */
class Book
{

    const ALLOWEDGENRES = ['AA', 'COMBGPN', 'MYS', 'FAN', 'CLAS', 'HF', 'HOR', 'ROM', 'SCIFI', 'SUSTHR', 'BIOAUTO', 'COOK', 'HIS', 'TRUCRI', 'OTH'];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Assert\PositiveOrZero
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(normalizer="trim", message="Book must have a title")
     * @Assert\Length(max = 30, maxMessage = "Title cannot be longer than {{ limit }} characters.")
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\Choice(choices=Book::ALLOWEDGENRES, message="Pick a valid genre.")
     */
    protected $genre;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank(normalizer="trim", message="Book must have a publisher")
     * @Assert\Length(max = 40, maxMessage = "Publisher cannot be longer than {{ limit }} characters.")
     */
    protected $publisher;

    /**
     * @ORM\Column(type="array")
     */
    protected $authors;

    /**
     * @ORM\Column(type="string", length=140)
     * @Assert\NotBlank(normalizer="trim", message="Book must have a summary")
     * @Assert\Length(max = 140, maxMessage = "Summary cannot be longer than {{ limit }} characters.")
     */
    protected $bookSummary;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isAvailable;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $dateAdded;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="userID", referencedColumnName="id")
     */
    protected User $owner;


    /**
     * ================================================================
     *                      GETTERS AND SETTERS
     * ================================================================
     */


    public function setDateAdded()
    {
        $this->dateAdded = new \DateTime("now");
    }

    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param mixed $genre
     */
    public function setGenre($genre): void
    {
        $this->genre = $genre;
    }

    /**
     * @return mixed
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @param mixed $publisher
     */
    public function setPublisher($publisher): void
    {
        $this->publisher = $publisher;
    }

    /**
     * @return mixed
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @param mixed $authors
     */
    public function setAuthors($authors): void
    {
        $this->authors = $authors;
    }

    /**
     * @return mixed
     */
    public function getBookSummary()
    {
        return $this->bookSummary;
    }

    /**
     * @param mixed $bookSummary
     */
    public function setBookSummary($bookSummary): void
    {
        $this->bookSummary = $bookSummary;
    }

    /**
     * @return mixed
     */
    public function getIsAvailable()
    {
        return $this->isAvailable;
    }

    /**
     * @param mixed $isAvailable
     */
    public function setIsAvailable($isAvailable): void
    {
        $this->isAvailable = $isAvailable;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }


}