<?php

require_once "{$_SERVER["DOCUMENT_ROOT"]}/init/index.php";
require_once 'ProductQuery.class.php';

try {
  $productQuery = new ProductQuery();
  $sql = $productQuery->getProductsQuery();
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  $products = $stmt->fetchAll();
  echo json_encode($products);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

$conn = null;
