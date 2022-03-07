<?php require_once "src/view/top.php"; ?>

<!-- Form name & input(input, select tag) id attribute, option value use underscore seperated naming for easier accessing object property of input name in PHP and its value in JS-->
<!-- <iframe name="exiter" style="display:none;"></iframe> -->
<!-- <form target="exiter"> -->

<div class="products-container">
  <div class="header">
    <div class="title">Product List</div>
    <div class="products-actions">
      <!-- <select class="action-select" name="products_action" id="products_action">
        <option value="mass_delete" selected>Mass Delete</option>
        <option value="delete_all">Delete All</option>
      </select>
      <button id="product-list-action-btn" class="action-apply-btn" type="submit">Apply</button> -->
      <button onclick="navToAddItemPage()">
        ADD
      </button>
      <button id="delete-product-btn" onclick="deleteProducts()">
        MASS DELETE
      </button>
    </div>
  </div>
  <div id="products" class="products">
  </div>
</div>
<?php
$jsFileDependencies = array('ajax', 'form');
foreach ($jsFileDependencies as $jsFileDependency) {
  echo "<script src='/src/js/{$jsFileDependency}.js'></script>";
}
?>
<script src="/src/js/list.js"></script>
<?php require_once 'src/view/bottom.php' ?>