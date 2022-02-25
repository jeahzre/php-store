<?php

require_once "{$_SERVER["DOCUMENT_ROOT"]}/init/index.php";
require_once 'GetProduct.class.php';
require_once 'ProductQuery.class.php';

$getProductObject = new GetProduct($conn);
$result = $getProductObject->getProduct();

echo json_encode($result);
$conn = null;
