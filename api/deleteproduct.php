<?php

require_once "{$_SERVER["DOCUMENT_ROOT"]}/init/index.php";
require_once 'ProductQuery.class.php';
require_once 'DeleteProduct.class.php';

if (
  isset($_POST["delete_product"]) &&
  count($_POST["delete_product"]) > 0
) {
  $deleteProductObject = new DeleteProduct($conn);
  $result = $deleteProductObject->deleteProduct();
}

if ($result) {
  echo json_encode($result);
}

$conn = null;
