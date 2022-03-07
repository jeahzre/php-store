<!-- Form name & input(input, select tag) id attribute, option value use underscore seperated naming for easier accessing object property of input name in PHP and its value in JS-->
<!-- <iframe name="exiter" style="display:none;"></iframe> -->
<?php require_once 'src/view/top.php' ?>
<form action="/api/addproduct.php" method="post" class="add-product-form" id="product_form">
  <div class="header">
    <div class="title">Product Add</div>
    <div class="products-actions">
      <button class="add-product-button" id="add-product-button" onclick="addProduct(event)">
        Save
      </button>
      <button class="add-cancel-button" type="button" id="add-cancel-button" onclick="navToListPage()">
        Cancel
      </button>
    </div>
  </div>
  <div class="top-message" id="top-message"></div>
  <table class="add-product-table-form" id="add-product-table-form">
    <tbody>
      <tr>
        <td>
          <label for="sku">SKU</label>
        </td>
        <td>
          <input type="text" name="sku" id="sku" class="add_product">
        </td>
      </tr>
      <tr>
        <td>
          <label for="name">Name</label>
        </td>
        <td>
          <input type="text" name="product_name" id="name" class="add_product">
        </td>
      </tr>
      <tr>
        <td>
          <label for="price">Price</label>
        </td>
        <td>
          <input type="number" name="price" id="price" id="price" class="add_product">
        </td>
      </tr>
      <tr>
        <td>
          <label for="productType">Type Switcher</label>
        </td>
        <td>
          <select id="productType" name="product_type" class="add_product">
            <option value="DVD" id="DVD">DVD</option>
            <option value="Furniture" id="Furniture">Furniture</option>
            <option value="Book" id="Book">Book</option>
          </select>
        </td>
      </tr>
    </tbody>
    <tbody id="attribute-form">
      <!-- Dynamically change form -->
      <!-- renderRow() -->
    </tbody>
    <tr>
      <td id="product-type-form-description"></td>
    </tr>
  </table>
</form>
<?php
$jsFileDependencies = array('ajax', 'form');
foreach ($jsFileDependencies as $jsFileDependency) {
  echo "<script src='/src/js/{$jsFileDependency}.js'></script>";
}
?>
<script>
  const renderProductTypeFormRow = (option, isArrayOption) => {
    const formattedOption = capitalize(option);
    const trElement = document.createElement('tr');
    trElement.id = `tr-${option}`;
    trElement.innerHTML = `
    <td>
      <label for="${option}">${formattedOption}</label>
    </td>
    <td>
      <input type="number" name="${option}" id="${option}" placeholder="${formattedOption}" class="add_product">
    </td>
    `;
    const attributeForm = document.getElementById('attribute-form');
    if (!isArrayOption) {
      attributeForm.innerHTML = '';
    }
    attributeForm.appendChild(trElement);
  }
</script>
<script src="/src/js/add-product.js"></script>
<?php require_once 'src/view/bottom.php' ?>