<?php

require_once "{$_SERVER["DOCUMENT_ROOT"]}/init/index.php";
spl_autoload_register(function ($class) {
  $parts = explode('\\', $class);
  require $parts[0] . '.class.php';
});
require_once 'function.php';

function addErrorMessages()
{
  // Add error messages to JSON Object
  global $JSONObject;
  // $requiredFields -> 'input_name' => 'display_name'
  $requiredFields = array('sku' => 'SKU', 'product_name' => 'Name', 'price' => 'Price');
  $errorMessagesObject = new stdClass();
  foreach ($requiredFields as $requiredField => $requiredFieldToDisplay) {
    if (empty($_POST[$requiredField])) {
      $errorMessage = "{$requiredFieldToDisplay} is required";
      $errorMessagesObject->$requiredField = $errorMessage;
    }
  }
  $JSONObject->errorMessagesObject = $errorMessagesObject;
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
  global $JSONObject;

  foreach ($mandatoryInputs as $mandatoryInput) {
    if (!$_POST[$mandatoryInput]) {
      $JSONObject->errorMessage = 'Please, submit required data';
      return false;
    }
  }
  return true;
};

function addProduct()
{
  global $conn, $JSONObject;

  try {
    $productQuery = new ProductQuery();
    $sql = $productQuery->getInsertProductQuery();
    $paramKeyValue = array(
      'sku' => $_POST['sku'],
      'product_name' => $_POST['product_name'],
      'price' => $_POST['price']
    );
    $statement = $conn->prepare($sql);
    foreach ($paramKeyValue as $key => &$value) {
      $statement->bindParam($key, $value);
    }
    $statement->execute();
  } catch (PDOException $e) {
    $JSONObject->errorMessage = $sql . "\n" . $e->getMessage();
  }
}

function addProductAttribute()
{
  global $conn, $JSONObject;

  // Add product attribute
  if (isset($_POST["product_type"])) {
    function getProductAttribute()
    {
      $productType = $_POST["product_type"]; // example: DVD, Furniture, and Book
      $productTypeClassName = "ProductType\\$productType";
      $productTypeObject = new $productTypeClassName();
      $productAttribute = $productTypeObject->getAttribute(); // example: size, weight, height, width, and length
      return $productAttribute;
    }

    function getProductAttributeObject($productAttribute)
    {
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

    $productAttribute = getProductAttribute();
    $productAttributeObject = getProductAttributeObject($productAttribute);
    $attribute_id = getProductAttributeID($productAttributeObject);
    $productAttributeQuery = new ProductAttributeQuery();
    // Insert into 'product_attribute' table
    try {
      $sql = $productAttributeQuery->getInsertProductAttributeQuery();
      $statement = $conn->prepare($sql);
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
      $JSONObject->errorMessage = $sql . "\n" . $e->getMessage();
    }

    // Insert into '{productAttribute}_value' table
    try {
      $firstLetterUpProductAttribute = ucwords($productAttribute);
      $sql = $productAttributeQuery->{"get{$firstLetterUpProductAttribute}Query"}();
      $statement = $conn->prepare($sql);
      $productAttributeObject->execSql($statement);
      // echo "New {$productAttribute} value created \n";
    } catch (PDOException $e) {
      echo $sql . "\n" . $e->getMessage();
    }
  } else {
    $JSONObject->errorMessage = 'Please, provide product type';
  }
}

function checkIDExistence()
{
  global $conn, $JSONObject;

  $productQuery = new ProductQuery();
  $checkIDExistenceQuery = $productQuery->getCheckIDExistenceQuery();
  $stmt = $conn->prepare($checkIDExistenceQuery);
  $stmt->bindParam('sku', $_POST['sku']);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row['NumberOfProducts']) {
    $JSONObject->errorMessage = 'ID has already exists';
  }
}

// POST method variable values
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

// JSON Object to pass to JS as response
class JSONObject
{
  public function __construct()
  {
    $this->errorMessage = '';
    $this->successMessage = '';
    $this->errorMessagesObject = (object)[];
  }
}
$JSONObject = new stdClass();

try {
  modifyPostValue($posts);
  checkIDExistence();
  addErrorMessages();
  $mandatoryInputs = getMandatoryInputs();
  checkMandatoryInputFilled($mandatoryInputs);
  $hasErrorMessage = isset($JSONObject->errorMessage);

  if ($hasErrorMessage) {
    // If mandatory inputs haven't been filled all.
    throw new Exception($JSONObject->errorMessage);
  }

  addProduct();
  addProductAttribute();
  $JSONObject->successMessage = 'Added Successfully';
  // Pass JSON Object
} catch (Exception $e) {
  // $JSONObject->errorMessage = $e->getMessage();
}

echo json_encode($JSONObject);
$conn = null;
