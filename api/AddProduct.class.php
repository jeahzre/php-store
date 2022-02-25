<?php
class AddProduct
{
  function __construct($conn, $JSONObject)
  {
    $this->conn = $conn;
    $this->JSONObject = $JSONObject;
    $this->productAttributeQuery = new ProductAttributeQuery();
    $this->productQuery = new ProductQuery();
    $this->addErrorMessages();
    $mandatoryInputs = $this->getMandatoryInputs();
    $this->checkMandatoryInputFilled($mandatoryInputs);
    $this->checkIDExistence();
  }

  function addErrorMessages()
  {
    // $requiredFields -> 'input_name' => 'display_name'
    $requiredFields = array('sku' => 'SKU', 'product_name' => 'Name', 'price' => 'Price');
    $errorMessagesObject = new stdClass();
    foreach ($requiredFields as $requiredField => $requiredFieldToDisplay) {
      if (empty($_POST[$requiredField])) {
        $errorMessage = "{$requiredFieldToDisplay} is required";
        $errorMessagesObject->$requiredField = $errorMessage;
      }
    }
    if (count((array)$errorMessagesObject) > 0) {
      $this->JSONObject->errorMessagesObject = $errorMessagesObject;
    }
  }

  function addSuccessMessage()
  {
    $this->JSONObject->successMessage = 'Added Successfully';
  }

  function getMandatoryInputs()
  {
    // Each $mandatoryInputs same as form input names
    $productTypeClassName = "ProductType\\{$_POST['product_type']}";
    $productType = new $productTypeClassName();
    $productAttribute = $productType->getAttributes();
    $staticMandatoryInputs = ['sku', 'product_name', 'price', 'product_type'];
    if (is_array($productAttribute)) {
      $mandatoryInputs = [...$staticMandatoryInputs, ...$productAttribute];
    } else {
      $mandatoryInputs = [...$staticMandatoryInputs, $productAttribute];
    }

    return $mandatoryInputs;
  }

  function checkMandatoryInputFilled($mandatoryInputs)
  {
    foreach ($mandatoryInputs as $mandatoryInput) {
      if (!$_POST[$mandatoryInput]) {
        $this->JSONObject->errorMessage = 'Please, submit required data';
        return false;
      }
    }
    return true;
  }

  function addProduct()
  {
    try {
      $hasErrorMessage = isset($this->JSONObject->errorMessage) ||
        (isset($this->JSONObject->errorMessagesObject) && count((array)$this->JSONObject->errorMessagesObject) > 0);
      if ($hasErrorMessage) {
        // If mandatory inputs haven't been filled all.
        throw new Exception();
      }
      $sql = $this->productQuery->getInsertProductQuery();
      $paramKeyValue = array(
        'sku' => $_POST['sku'],
        'product_name' => $_POST['product_name'],
        'price' => $_POST['price']
      );
      $statement = $this->conn->prepare($sql);
      foreach ($paramKeyValue as $key => &$value) {
        $statement->bindParam($key, $value);
      }
      $statement->execute();
      $this->addProductAttribute();
      $this->addSuccessMessage();
    } catch (PDOException | Exception $e) {
      // throw new Exception();
    } finally {
      $this->conn = null;
      return $this->JSONObject;
    }
  }

  function getProductAttribute()
  {
    $productType = $_POST["product_type"]; // example: DVD, Furniture, and Book
    $productTypeClassName = "ProductType\\$productType";
    $productTypeObject = new $productTypeClassName();
    $productAttribute = $productTypeObject->getAttribute(); // example: size, weight, height, width, and length
    return $productAttribute;
  }

  function getProductAttributeObject()
  {
    $productAttribute = $this->getProductAttribute();
    $firstLetterUpProductAttribute = ucwords($productAttribute);
    $productAttributeClassName = "ProductAttribute\\{$firstLetterUpProductAttribute}";
    $productAttributeObject = new $productAttributeClassName();
    return $productAttributeObject;
  }

  function getProductAttributeID($productAttributeObject)
  {
    $attribute_id = $productAttributeObject->attribute_id;
    return $attribute_id;
  }

  function insertIntoProductAttribute()
  {
    // Insert into 'product_attribute' table
    $productAttributeObject = $this->getProductAttributeObject();
    $attribute_id = $this->getProductAttributeID($productAttributeObject);
    try {
      $sql = $this->productAttributeQuery->getInsertProductAttributeQuery();
      $statement = $this->conn->prepare($sql);
      $paramKeyValue = array(
        'sku' => $_POST['sku'],
        'attribute_id' => $attribute_id
      );
      foreach ($paramKeyValue as $key => &$value) {
        $statement->bindParam($key, $value);
      }
      $statement->execute();
      // echo "New product_attribute record created \n";
    } catch (PDOException $e) {
      $this->JSONObject->errorMessage = $sql . "\n" . $e->getMessage();
      throw new Exception();
    }
  }

  function insertIntoProductAttributeValue()
  {
    $productAttribute = $this->getProductAttribute();
    $productAttributeObject = $this->getProductAttributeObject();
    // Insert into '{productAttribute}_value' table
    try {
      $firstLetterUpProductAttribute = ucwords($productAttribute);
      $sql = $this->productAttributeQuery->{"get{$firstLetterUpProductAttribute}Query"}();
      $statement = $this->conn->prepare($sql);
      $productAttributeObject->execSql($statement);
      // echo "New {$productAttribute} value created \n";
    } catch (PDOException $e) {
      // echo $sql . "\n" . $e->getMessage();
      throw new Exception();
    }
  }

  function addProductAttribute()
  {
    // Add product attribute
    if (isset($_POST["product_type"])) {
      try {
        $this->insertIntoProductAttribute();
        $this->insertIntoProductAttributeValue();
      } catch (Exception $e) {
        throw new Exception();
      }
    }
  }

  function checkIDExistence()
  {
    $checkIDExistenceQuery = $this->productQuery->getCheckIDExistenceQuery();
    $stmt = $this->conn->prepare($checkIDExistenceQuery);
    $stmt->bindParam('sku', $_POST['sku']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['NumberOfProducts']) {
      $this->JSONObject->errorMessage = 'ID has already exists';
      throw new Exception();
    }
  }
}
