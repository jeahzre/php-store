<?php

// q contains types seperated by ","
$q = $_GET["q"];
require_once 'ProductType.class.php';

$JSONObject = new stdClass();
$className = "ProductType\\{$q}";
$productType = new $className();
$JSONObject->attribute = $productType->getAttributes();
$JSONObject->attributeDescription = $productType->getAttributeDescription();
echo json_encode($JSONObject);
