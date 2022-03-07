<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    <?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    $path = "{$root}/vendor/scssphp/scssphp/scss.inc.php";
    require_once $path;

    use ScssPhp\ScssPhp\Compiler;

    $compiler = new Compiler();

    echo $compiler
      ->compileString(file_get_contents("{$root}/src/css/index.scss"))
      ->getCss();
    ?>
  </style>
</head>

<body id="body">
  <?php require_once "navigation.php"; ?>