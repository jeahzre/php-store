<?php
require_once 'middleware.php';

use Controller\DeleteProduct;

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
