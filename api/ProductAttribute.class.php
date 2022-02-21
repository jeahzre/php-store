<?php

namespace ProductAttribute;

abstract class ProductAttribute
{
  public $attribute_id;
  abstract public function execSql($statement);
}

class Size extends ProductAttribute
{
  public $attribute_id = 1;
  public function execSql($statement)
  {
    $paramKeyValue = array(
      'attribute_id' => $this->attribute_id,
      'sku' => $_POST['sku'],
      'size' => $_POST['size']
    );
    foreach ($paramKeyValue as $key => &$value) {
      $statement->bindParam($key, $value);
    }
    $statement->execute();
  }
}

class Weight extends ProductAttribute
{
  public $attribute_id = 2;
  public function execSql($statement)
  {
    $paramKeyValue = array(
      'attribute_id' => $this->attribute_id,
      'sku' => $_POST['sku'],
      'weight' => $_POST['weight']
    );
    foreach ($paramKeyValue as $key => &$value) {
      $statement->bindParam($key, $value);
    }
    $statement->execute();
  }
}

class Dimension extends ProductAttribute
{
  public $attribute_id = 3;
  public function execSql($statement)
  {
    $dimensionOptionID = new DimensionOptionID();
    foreach (get_object_vars($dimensionOptionID) as $dimensionOption => $dimensionID) {
      $paramKeyValue = array(
        'attribute_id' => $this->attribute_id,
        'sku' => $_POST['sku'],
        'dimension_id' => $dimensionID,
        'dimension_value' => $_POST[$dimensionOption]
      );

      foreach ($paramKeyValue as $key => &$value) {
        $statement->bindParam($key, $value);
      }
      $statement->execute();
    }
  }
}

class DimensionOptionID
{
  public $height = 1;
  public $width = 2;
  public $length = 3;
}
