<?php
$q = $_GET["q"];
require_once 'ProductType.class.php';

$JSONObject = new stdClass();
$className = "ProductType\\{$q}";
$productType = new $className();
$modifiedJSONObject = $productType->getProductTypeForm($JSONObject);
echo json_encode($JSONObject);
