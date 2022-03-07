<?php
require_once 'middleware.php';

use Controller\GetProduct;

$getProductObject = new GetProduct($conn);
$result = $getProductObject->getProduct();

echo json_encode($result);
$conn = null;
