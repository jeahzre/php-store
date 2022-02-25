<?php 
class DeleteProduct
{
  function __construct($conn)
  {
    $this->conn = $conn;
    $this->productQueryObject = new ProductQuery();
  }

  function tryCatchExecSQL($sql, $key, $value)
  {
    try {
      $statement = $this->conn->prepare($sql);
      $statement->bindParam($key, $value);
      $statement->execute();
    } catch (PDOException $e) {
      // echo $sql . "\n" . $e->getMessage();
      throw new Exception();
    }
  }

  function deleteProduct()
  {
    // On mass delete product
    $sql = $this->productQueryObject->getMassDeleteQuery();
    try {
      foreach ($_POST["delete_product"] as $productToDeleteSKU) {
        $this->tryCatchExecSQL($sql, 'sku', $productToDeleteSKU);
      }
      return true;
    } catch (Exception $e) {
      return false;
    }
  }
}
?>