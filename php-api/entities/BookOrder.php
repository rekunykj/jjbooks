<?php

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="bookorders")
 */
class BookOrder
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Assert\PositiveOrZero
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $checkOutDate;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $returnDate;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isApproved;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isComplete;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="receiverID", referencedColumnName="id")
     */
    protected User $receiver;

    /**
     * @ORM\ManyToOne(targetEntity="Book")
     * @ORM\JoinColumn(name="bookID", referencedColumnName="id")
     */
    protected Book $book;


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
    public function getCheckOutDate()
    {
        return $this->checkOutDate;
    }

    /**
     * @param mixed $checkOutDate
     */
    public function setCheckOutDate(): void
    {
        $this->checkOutDate = new \DateTime("now");
    }

    /**
     * @return mixed
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * @param mixed $returnDate
     */
    public function setReturnDate()
    {
        $this->returnDate = new \DateTime("now");
        $this->returnDate->add(new \DateInterval("P14D"));

    }

    /**
     * @return mixed
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }

    /**
     * @param mixed $isApproved
     */
    public function setIsApproved($isApproved): void
    {
        $this->isApproved = $isApproved;
    }

    /**
     * @param mixed
     */
    public function getIsComplete()
    {
        return $this->isComplete;
    }

    /**
     * @param $isComplete
     */
    public function setIsComplete($isComplete): void
    {
        $this->isComplete = $isComplete;
    }

    /**
     * @return User
     */
    public function getReceiver(): User
    {
        return $this->receiver;
    }

    /**
     * @param User $receiver
     */
    public function setReceiver(User $receiver): void
    {
        $this->receiver = $receiver;
    }

    /**
     * @return Book
     */
    public function getBook(): Book
    {
        return $this->book;
    }

    /**
     * @param Book $book
     */
    public function setBook(Book $book): void
    {
        $this->book = $book;
    }

}