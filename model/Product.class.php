<?php

namespace Model\Product;

abstract class Product
{
  public $attribute, $attributes, $attributeDescription;

  public function getAttribute()
  {
    return $this->attribute;
  }

  public function getAttributeDescription()
  {
    return $this->attributeDescription;
  }
  
  public function getAttributes() {
    if (isset($this->attributes)) {
      return $this->attributes;
    } else {
      return $this->attribute;
    }
  }

  // public function getProductTypeForm()
  // {
  //   $JSONObject = new \stdClass();
  //   $JSONObject->attribute = $this->getAttributes();
  //   $JSONObject->attributeDescription = $this->getAttributeDescription();
  //   return $JSONObject;
  // }
}