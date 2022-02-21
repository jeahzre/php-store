<?php

// header("Content-Type: application/json");

function process_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function modifyPostValue($vars)
{
  foreach ($vars as $var) {
    if (isset($_POST[$var])) {
      // If form data comes from form submit
      $varValue = process_input($_POST[$var]);
      $_POST[$var] = $varValue;
    }
  }
}
