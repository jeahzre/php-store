<?php 

namespace Controller;
use \PDO;
use \PDOException;
use Model\ProductQuery;

class GetProduct
{
  function __construct($conn)
  {
    $this->conn = $conn;
    $this->productQuery = new ProductQuery();
  }

  function getProduct()
  {
    try {
      $sql = $this->productQuery->getProductsQuery();
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      // set the resulting array to associative
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $products = $stmt->fetchAll();
      return $products;
    } catch (PDOException $e) {
      // throw new Exception();
    }
  }
}
?>