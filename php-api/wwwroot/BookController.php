<?php
use Doctrine\Instantiator\Exception\ExceptionInterface;
use Doctrine\ORM\EntityManager;

use Doctrine\ORM\ORMException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validation;

require_once '../entities/Book.php';


class BookController
{

    /**
     * Handles GET request to return an array of book objects
     * @param EntityManager $em
     * @return array|int|string
     */
    public static function getBook(EntityManager $em)
    {
        $return = null;

        $qb = $em->createQueryBuilder();
        $qb->select('b')
            ->from('Book', 'b');

        $return = $qb->getQuery()->getArrayResult();
        if(empty($return))
        {
            http_response_code(404);
        }
        return $return;
    }

    /**
     * Handles POST request to add a new book to the database
     * @param EntityManager $em
     * @param array $reqData
     * @param Book $newBook
     * @return array|mixed
     */
    public static function postBook(EntityManager $em, array $reqData, Book $newBook)
    {
        $return = null;
        $violations = [];
        $newBook->setDateAdded();

        $currentUser = $em->find(User::class, $reqData['owner']['id']);
        $newBook->setOwner($currentUser);

        if(self::populateBookObject($reqData, $newBook, $violations))
        {
            try {
                $em->persist($newBook);
                $em->flush();
                http_response_code(201);
                $return = $newBook->getId();
            }
            catch (ORMException $error) {
                http_response_code(500);
                $return = ['errorMessage' => $error->getMessage()];
            }
        }
        else {
            http_response_code(422);
            $return = $violations;
        }

        return $return;
    }

    /**
     * Handles PUT request to update a book in the database
     * @param EntityManager $em
     * @param array $newData
     * @param Book $bookFromDB
     * @return array|mixed
     */
    public static function putBook(EntityManager $em, array $newData, Book $bookFromDB)
    {
        $return = null;
        $violations = [];

        if(self::populateBookObject($newData, $bookFromDB, $violations))
        {
            try {
                $em->flush();
                $return = $bookFromDB->getId();
            }
            catch (ORMException $error) {
                http_response_code(500);
                $return = ['errorMessage' => $error->getMessage()];
            }
        }
        else {
            http_response_code(422);
            $return = $violations;
        }

        return $return;
    }

    /**
     * Handles DELETE requests to remove a book from the database
     * @param EntityManager $em
     * @param array $reqData
     * @param Book|null $bookToDelete
     */
    public static function deleteBook(EntityManager $em, array $reqData, ?Book $bookToDelete)
    {
        $return = null; // Store the resulting data
        if(is_null($bookToDelete)) {
            http_response_code(404);
            $return = $reqData;
        }
        else {
            if(self::checkValues($bookToDelete, $reqData)) {
                try {
                    $em->remove($bookToDelete);

                    $em->flush();

                    http_response_code(204);
                    $return = null;
                } catch (ORMException $e) {
                    http_response_code(205);
                    $return = $bookToDelete;
                }
            } else {
                //Does not match data in database
                http_response_code(422);
                $return = ['errorMessage' => 'The Book information does not correspond to the entity in the database'];
            }
        }
        return $return;
    }


    /*
     * HELPER FUNCTIONS
     */


    /**
     * Helper method that will populate the book object with data from the JSON request
     * @param array $reqData
     * @param Book $newBook
     * @param array $violations
     * @return bool
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    private static function populateBookObject(array $reqData, Book &$newBook, array &$violations)
    {
        $serializer = new Serializer([new ObjectNormalizer()], []);

        try {
            $serializer->denormalize($reqData, Book::class, null, [ObjectNormalizer::OBJECT_TO_POPULATE => $newBook,
                ObjectNormalizer::IGNORED_ATTRIBUTES => ['id', 'owner']]);

            $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
            foreach ($validator->validate($newBook) as $violation) {
                $violations[$violations->getPropertyPath()] = $violation->getMessage();
            }

        } catch (ExceptionInterface $error) {
            $violations['errorMessage'] = $error->getMessage();
        }

        return empty($violations);
    }

    /**
     * Helper method for comparing the object values in the DELETE handler. Will return true if all fields match.
     * @param Book $bookToDelete
     * @param array $reqData
     * @return bool IsEqual
     */
    private static function checkValues(Book $bookToDelete, array $reqData)
    {
        //Compare author arrays
        $arraysEqual = false;
        foreach ($bookToDelete->getAuthors() as $author)
        {
            foreach ($reqData['authors'] as $author2)
            {
                if($author === $author2)
                {
                    $arraysEqual = true;
                }
            }
        }


        if($reqData['title'] == $bookToDelete->getTitle() &&
        $reqData['id'] == $bookToDelete->getId() &&
        $reqData['genre'] == $bookToDelete->getGenre() &&
        $reqData['publisher'] == $bookToDelete->getPublisher() &&
        $arraysEqual &&
        $reqData['bookSummary'] == $bookToDelete->getBookSummary() &&
        $reqData['isAvailable'] == $bookToDelete->getIsAvailable() &&
        $reqData['dateAdded'] == $bookToDelete->getDateAdded() &&
        $reqData['owner'] == $bookToDelete->getOwner())
        {
            return true;
        }
        else {
            return false;
        }
    }

}