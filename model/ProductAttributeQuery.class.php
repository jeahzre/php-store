<?php

namespace Model;

class ProductAttributeQuery
{
  public $dimensionOptions = ["height", "width", "length"];

  public function getSizeQuery()
  {
    return "INSERT INTO `size_value` (attribute_id, sku, size_value) VALUES (:attribute_id, :sku, :size)";
  }

  public function getWeightQuery()
  {
    return "INSERT INTO `weight_value` (attribute_id, sku, weight_value) VALUES (:attribute_id, :sku, :weight)";
  }

  public function getDimensionQuery()
  {
    return "INSERT INTO `dimension_value` (attribute_id, sku, dimension_id, dimension_value ) VALUES (:attribute_id, :sku, :dimension_id, :dimension_value)";
  }

  public function getInsertProductAttributeQuery()
  {
    return "INSERT INTO `product_attribute` (sku, attribute_id)
    VALUES (:sku, :attribute_id)";
  }
}
