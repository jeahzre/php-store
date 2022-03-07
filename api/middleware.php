<?php
// Run every request
require_once "{$_SERVER["DOCUMENT_ROOT"]}/init/index.php";

$nameSpaceToFolderMap = [
  'Controller' => '../controller',
  'Model' => '../model',
  'Helper' => '../helper'
];

spl_autoload_register(function ($class) use ($nameSpaceToFolderMap) {
  $parts = explode('\\', $class);
  $lastIndexOfParts = count($parts) - 1;
  $namespaces = array_slice($parts, 0, $lastIndexOfParts);
  if (count($parts) > 1) {
    $relativeFilePath = '';
    foreach ($namespaces as $index => $namespace) {
      if (isset($nameSpaceToFolderMap[$namespace]) || array_key_exists($namespace, $nameSpaceToFolderMap)) {
        $folderOfNamespace = $nameSpaceToFolderMap[$namespace];
        if ($index === 0) {
          $relativeFilePath .= "{$folderOfNamespace}";
        } else {
          $relativeFilePath .= "/{$folderOfNamespace}";
        }
      }
    }

    $filePath = "{$relativeFilePath}/{$parts[$lastIndexOfParts]}.class.php";
    if (file_exists($filePath)) {
      require $filePath;
    } else {
      // require file that contains classes
      $secondToLastIndexOfParts = count($parts) - 2;
      $anotherFilePath = "{$relativeFilePath}/{$parts[$secondToLastIndexOfParts]}.class.php";
      require $anotherFilePath;
    }
  } else {
    require "{$parts[0]}.class.php";
  }
});
