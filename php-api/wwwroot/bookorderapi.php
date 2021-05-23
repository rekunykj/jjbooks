<?php
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

require_once '../init-db.php';
require_once '../entities/BookOrder.php';
require_once 'BookOrderController.php';

/**
 * PHP checking the raw string in pp://input - for put and delete requests
 */
$dataFile = file_get_contents('php://input');
$requestData = !empty($dataFile) ? json_decode( $dataFile, true) : $_REQUEST;

$resultToSend = ''; //initialize string to encode with json and send

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
            $resultToSend = BookOrderController::getBookOrders($entityManager, $requestData);
        break;

    case 'POST':
        $resultToSend = BookOrderController::postBookOrder($entityManager, $requestData, new BookOrder());
        break;

    case 'PUT':
        $bookorderFromDB = $entityManager->find(BookOrder::class, $requestData['id']);
        $resultToSend = BookOrderController::putBookOrder($entityManager, $requestData, $bookorderFromDB);
        break;

    case 'OPTIONS':
        http_response_code(204);
        header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS'); //allow methods not including delete
        header('Access-Control-Allow-Headers: Content-type' );
        header('Access-Control-Max-Age: 43200'); //12 hours max age
        break;

    default:
        header('http/1.1 405 Method Not Allowed');
}

header('Access-Control-Allow-Origin:*');
header('Content-type:application/json');

$serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

//echo out in json format
echo $serializer->serialize($resultToSend, 'json');
