<?php

require_once "{$_SERVER["DOCUMENT_ROOT"]}/init/index.php";
require_once 'ProductQuery.class.php';

function tryCatchExecSQL($sql, $conn, $key, $value)
{
    try {
        $statement = $conn->prepare($sql);
        $statement->bindParam($key, $value);
        $statement->execute();
    } catch (PDOException $e) {
        echo $sql . "\n" . $e->getMessage();
    }
}

// On mass delete product
if (
  isset($_POST["delete_product"])
) {
    $deleteProductsQueryObject = new ProductQuery();
    if (count($_POST["delete_product"]) > 0) {
        foreach ($_POST["delete_product"] as $productToDeleteSKU) {
            $sql = $deleteProductsQueryObject->getMassDeleteQuery();
            tryCatchExecSQL($sql, $conn, 'sku', $productToDeleteSKU);
        }
        $result = true;
    }
}

if ($result) {
    echo json_encode($result);
}

$conn = null;
