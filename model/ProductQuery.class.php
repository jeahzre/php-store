<?php

namespace Model;

class ProductQuery
{
  public function getProductsQuery()
  {
    return "SELECT `product`.`sku`, `product`.`product_name`, `product`.`price`, `attribute`.`attribute_name`, `size_value`.`size_value`, `weight_value`.`weight_value`, `dimension_option`.`dimension_option`, `dimension_value`.`dimension_value` 
    FROM `product` 
    LEFT JOIN `product_attribute` 
    ON `product`.`sku` = `product_attribute`.`sku` 
    LEFT JOIN `attribute` 
    ON `product_attribute`.`attribute_id`=`attribute`.`attribute_id` 
    LEFT JOIN `size_value` 
    ON (`product`.`sku`=`size_value`.`sku` 
    AND `product_attribute`.`attribute_id`=`size_value`.`attribute_id`) 
    LEFT JOIN `weight_value`
    ON (`weight_value`.`attribute_id`=`product_attribute`.`attribute_id` 
    AND `weight_value`.`sku`=`product_attribute`.`sku`) 
    LEFT JOIN `dimension_value` 
    ON (`dimension_value`.`attribute_id`=`product_attribute`.`attribute_id` 
    AND `dimension_value`.`sku`=`product_attribute`.`sku`) 
    LEFT JOIN `dimension_option` 
    ON `dimension_option`.`dimension_id` = `dimension_value`.`dimension_id`
    ORDER BY `product`.`sku`";
  }

  public function getInsertProductQuery()
  {
    return "INSERT INTO `product` (sku, product_name, price)
    VALUES (:sku , :product_name, :price)";
  }

  public function getMassDeleteQuery()
  {
    return "DELETE FROM `product` WHERE `product`.`sku`= :sku";
  }

  public function getDeleteAllQuery()
  {
    return "DELETE FROM `product`";
  }

  public function getCheckIDExistenceQuery()
  {
    return "SELECT COUNT(`sku`) AS NumberOfProducts FROM `product` WHERE `product`.`sku` = :sku;";
  }
}
