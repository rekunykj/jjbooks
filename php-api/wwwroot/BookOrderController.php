<?php

use Doctrine\Instantiator\Exception\ExceptionInterface;
use Doctrine\ORM\EntityManager;

use Doctrine\ORM\ORMException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validation;

class BookOrderController
{

    /**
     * Handles GET request to return an array of book orders that pertain to the user
     * @param EntityManager $em
     * @param array $reqData
     * @return array|int|string
     */
    public static function getBookOrders(EntityManager $em, array $reqData)
    {
        $return = null;


        $qb = $em->createQueryBuilder();

        $qb->select('b', 'o')
            ->from('BookOrder', 'b')
            ->leftJoin('b.receiver', 'o')
            ->where('o = :owner')
            ->setParameter('owner', $reqData['id']);


        $return = $qb->getQuery()->getArrayResult();
        if (empty($return)) {
            http_response_code(404);
        }
        return $return;
    }

    /**
     * Handles POST request for adding a new book order to the database
     * @param EntityManager $em
     * @param array $reqData
     * @param BookOrder $newBookOrder
     * @return array|mixed
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public static function postBookOrder(EntityManager $em, array $reqData, BookOrder $newBookOrder)
    {
        $return = null;
        $violations = [];

        $currentUser = $em->find(User::class, $reqData['receiver']['id']);
        $newBookOrder->setReceiver($currentUser);

        $book = $em->find(Book::class, $reqData['book']['id']);
        $newBookOrder->setBook($book);

        $newBookOrder->setCheckOutDate();
        $newBookOrder->setReturnDate();
        $newBookOrder->setIsApproved(false);
        $newBookOrder->setIsComplete(false);

        if(self::populateBookOrderObject($reqData, $newBookOrder, $violations))
        {
            try {
                $em->persist($newBookOrder);
                $em->flush();
                http_response_code(201);
                $return = $newBookOrder->getId();
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
     * Handles PUT request to update a book orders data in the database
     * @param EntityManager $em
     * @param array $newData
     * @param BookOrder $bookOrderFromDB
     * @return array|mixed
     */
    public static function putBookOrder(EntityManager $em, array $newData, BookOrder $bookOrderFromDB)
    {
        $return = null;
        $violations = [];

        if(self::populateBookOrderObject($newData, $bookOrderFromDB, $violations))
        {
            try {
                $em->flush();
                $return = $bookOrderFromDB->getId();
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
     * @param array $reqData
     * @param BookOrder $newBookOrder
     * @param array $violations
     * @return bool
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    private static function populateBookOrderObject(array $reqData, BookOrder $newBookOrder, array $violations)
    {
        $serializer = new Serializer([new ObjectNormalizer()], []);

        try {
            $serializer->denormalize($reqData, BookOrder::class, null, [ObjectNormalizer::OBJECT_TO_POPULATE => $newBookOrder,
                ObjectNormalizer::IGNORED_ATTRIBUTES => ['id', 'receiver','book']]);
            $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
            foreach($validator->validate($newBookOrder) as $violation) {
                $violations[$violations->getPropertyPath()] = $violation->getMessage();
            }

        } catch (ExceptionInterface $error) {
            $violations['errorMessage'] = $error->getMessage();
        }

        return empty($violations);
    }

}