<?php

require_once 'middleware.php';

use Helper\Request;
use Controller\ChangeProductType;

$gets = ['q'];
$requestObject = new Request;
$requestObject->modifyGetValue($gets);

$q = $_GET['q'];

$changeProductTypeObject = new ChangeProductType();
$JSONObject = $changeProductTypeObject->getProductTypeForm($q);
echo json_encode($JSONObject);
