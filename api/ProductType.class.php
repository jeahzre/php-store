<?php

namespace ProductType;

require_once 'Product.class.php';

use Product\Product;

class DVD extends Product
{
  public $attribute = 'size';
  public $attributeDescription = 'Please, provide size';

  public function getAttribute()
  {
    return $this->attribute;
  }

  public function getAttributes()
  {
    return $this->attribute;
  }

  public function getAttributeDescription()
  {
    return $this->attributeDescription;
  }
}

class Book extends Product
{
  public $attribute = 'weight';
  public $attributeDescription = 'Please, provide weight';
  public function getAttribute()
  {
    return $this->attribute;
  }

  public function getAttributes()
  {
    return $this->attribute;
  }

  public function getAttributeDescription()
  {
    return $this->attributeDescription;
  }
}

class Furniture extends Product
{
  public $attribute = 'dimension';
  public $attributes = array('height', 'width', 'length');
  public $attributeDescription = 'Please, provide dimensions';

  public function getAttribute()
  {
    return $this->attribute;
  }

  public function getAttributes()
  {
    return $this->attributes;
  }

  public function getAttributeDescription()
  {
    return $this->attributeDescription;
  }
}
