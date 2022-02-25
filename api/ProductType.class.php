<?php

namespace ProductType;

require_once 'Product.class.php';

use Product\Product;

class DVD extends Product
{
  public $attribute = 'size';
  public $attributeDescription = 'Please, provide size';
}

class Book extends Product
{
  public $attribute = 'weight';
  public $attributeDescription = 'Please, provide weight';
}

class Furniture extends Product
{
  public $attribute = 'dimension';
  public $attributes = array('height', 'width', 'length');
  public $attributeDescription = 'Please, provide dimensions';
}
