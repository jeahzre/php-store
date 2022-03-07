<?php

namespace Helper;

class Request
{
  // POST method variable values
  public function __construct($posts=[], $gets=[])
  {
    $this->posts = $posts;
    $this->gets = $gets;
  }

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

  public function modifyGetValue() {
    foreach ($this->gets as $var)  {
      if (isset($_GET[$var])) {
        $varValue = $this->process_input($_GET[$var]);
        $_GET[$var] = $varValue;
      }
    }
  }
}
