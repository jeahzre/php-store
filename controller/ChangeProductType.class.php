<?php

namespace Controller;

class ChangeProductType
{
  function getProductTypeForm($q)
  {
    $className = "\Model\ProductType\\{$q}";
    $productType = new $className();
    
    $JSONObject = new \stdClass();
    $JSONObject->attribute = $productType->getAttributes();
    $JSONObject->attributeDescription = $productType->getAttributeDescription();
    return $JSONObject;
  }
}
