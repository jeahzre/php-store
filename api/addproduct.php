<?php
require_once 'middleware.php';

use Helper\Request;
use Controller\AddProduct;

$posts = [
  "sku",
  "product_name",
  "price",
  "product_type",
  "size",
  "weight",
  "height",
  "width",
  "length"
];

$form = new Request($posts);
$form->modifyPostValue();
$addProductObject = new AddProduct($conn);
// JSON Object to pass to JS as response
$JSONObject = $addProductObject->addProduct();

// Pass JSON Object
echo json_encode($JSONObject);
