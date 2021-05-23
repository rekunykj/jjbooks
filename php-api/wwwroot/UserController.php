<?php

use Doctrine\Instantiator\Exception\ExceptionInterface;
use Doctrine\ORM\EntityManager;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validation;


class UserController
{

    /**
     * Handles a GET request to return a users information to the front end via their
     * username
     * @param EntityManager $em
     * @param array $reqData
     * @return int|mixed|string
     */
    public static function getUser(EntityManager $em, array $reqData)
    {
        $return = null;
        $tempPassword = $reqData['password'];


        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from('User', 'u')
            ->where('u.username = :usernameSearched')
            ->setParameter('usernameSearched', $reqData['username']);

        try {
            $return = $qb->getQuery()->getSingleResult();

            if(hash_equals($return->getPassword(), self::HashPassword($reqData['password']))) {
                $return->setPassword($tempPassword);
                return $return; //Checks out, user exists and password is correct
            }
            else {
                $return = "Incorrect Username & or Password"; //Incorrect, forbid access and send back null object
                http_response_code(403);
            }


        } catch (NoResultException $e) {
            //Not found
            http_response_code(404);
        } catch (NonUniqueResultException $e) {
            http_response_code(500);
        }
        return $return;
    }

    /**
     * Handles POST request to add a new user to the database
     * @param EntityManager $em
     * @param array $reqData
     * @param User $newUser
     * @return array|mixed
     */
    public static function postUser(EntityManager $em, array $reqData, User $newUser)
    {
        $return = null;
        $violations = []; //Store errors
        //Hash Password beforehand and store it in the user object
        $hashedPassword = self::HashPassword($reqData['password']);
        $newUser->setPassword($hashedPassword);


        if(self::populateUserObject($reqData, $newUser, $violations))
        {
            try {
                $em->persist($newUser);
                $em->flush();
                http_response_code(201);
                $return = $newUser->getId();
            }
            catch (ORMException $error) {
                //Internal Server Error
                http_response_code(500);
                $return = ['errorMessage' => $error->getMessage()];
            }
            catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $duplicateError) {
                http_response_code(409);
                $return = ['errorMessage' => "Username already taken, try another username."];
            }
        }
        else {
            //Cannot process entity
            http_response_code(422);
            $return = $violations;
        }

        return $return;
    }

    /**
     * Handles PUT request to update a user in the database
     * @param EntityManager $em
     * @param array $newData
     * @param User $userFromDB
     * @return array|mixed
     */
    public static function putUser(EntityManager $em, array $newData, User $userFromDB)
    {
        $return = null;
        $violations = []; //Store any potential errors or violations

        if(self::populateUserObject($newData, $userFromDB, $violations))
        {
            try {
                $em->flush();
                $return = $userFromDB->getId();
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

    /*
     * HELPER FUNCTIONS
     */


    /**
     * Helper method that will populate the user object with data from the JSON request
     * @param array $reqData
     * @param User $newUser
     * @param array $violations
     * @return bool
     */
    private static function populateUserObject(array $reqData, User &$newUser, array &$violations)
    {
        $serializer = new Serializer([new ObjectNormalizer()], []);

        try {
            $serializer->denormalize($reqData, User::class, null, [ObjectNormalizer::OBJECT_TO_POPULATE => $newUser,
                ObjectNormalizer::IGNORED_ATTRIBUTES => ['id', 'password']]);

            $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
            foreach($validator->validate($newUser) as $violation) {
                $violations[$violations->getPropertyPath()] = $violation->getMessage();
            }

        } catch (ExceptionInterface $error) {
            $violations['errorMessage'] = $error->getMessage();
        }

        return empty($violations);
    }

    /**
     * Uses our HASH function (SHA-256)
     * To encrypt the password before storing it on the database
     * @param string $passwordToHash
     * @return string hashed password
     */
    private static function HashPassword(string &$passwordToHash)
    {
        return $hashedPassword = hash("sha256", $passwordToHash);
    }

}