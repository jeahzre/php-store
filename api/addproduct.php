<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/init/index.php";
spl_autoload_register(function ($class) {
  $parts = explode('\\', $class);
  require $parts[0] . '.class.php';
});

// JSON Object to pass to JS as response
$JSONObject = new stdClass();

$form = new Form();
$form->modifyPostValue();
$addProductObject = new AddProduct($conn, $JSONObject);
$modifiedJSONObject = $addProductObject->addProduct();

// Pass JSON Object
echo json_encode($modifiedJSONObject);
