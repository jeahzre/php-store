<?php

class Form
{
  // POST method variable values
  public $posts = [
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

  public function process_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  public function modifyPostValue()
  {
    foreach ($this->posts as $var) {
      if (isset($_POST[$var])) {
        // If form data comes from form submit
        $varValue = $this->process_input($_POST[$var]);
        $_POST[$var] = $varValue;
      }
    }
  }
}
