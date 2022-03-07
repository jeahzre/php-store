const currencySymbol = "$";
const attributeNameToValueMap = {
  size: "size_value",
  weight: "weight_value",
  dimension: "dimension_value",
};
const attributeNameToUnitMap = {
  size: "MB",
  weight: "kg",
  // dimension: 'm'
};
const dimensionFormatSequence = {
  height: 0,
  width: 1,
  length: 2,
};

const formatProductObject = (rawProducts) => {
  // const {attribute_name} = rawProduct (each rawProducts);
  let formattedProducts = [
    //  {
    //      "sku",
    //      "product_name",
    //      "price",
    //      "attribute: {
    //          "attribute_name": "attributeNameToValueMap[attribute_name]"
    //      }
    //  }
  ];
  rawProducts.map((rawProduct) => {
    const { sku, product_name, price, attribute_name, dimension_option } =
      rawProduct;
    const isInFormattedProducts = formattedProducts.some(
      (formattedProduct) => formattedProduct.sku === sku
    );
    const formatAttribute = (
      attribute_name,
      dimension_option,
      rawValueName,
      rawProduct,
      isInFormattedProducts,
      formattedProduct = null
    ) => {
      const attributeToAdd = {};
      if (attribute_name === "dimension") {
        if (isInFormattedProducts) {
          const {
            attribute: { [attribute_name]: dimension },
          } = formattedProduct;
          attributeToAdd[attribute_name] = {
            ...dimension,
            [dimension_option]: rawProduct[rawValueName],
          };
        } else {
          attributeToAdd[attribute_name] = {
            [dimension_option]: rawProduct[rawValueName],
          };
        }
      } else {
        attributeToAdd[attribute_name] = rawProduct[rawValueName];
      }
      return attributeToAdd;
    };

    if (!isInFormattedProducts) {
      let attributeToAdd = {};
      if (attribute_name) {
        // Get rawValueName by attribute_name
        const rawValueName = attributeNameToValueMap[attribute_name];
        attributeToAdd = formatAttribute(
          attribute_name,
          dimension_option,
          rawValueName,
          rawProduct,
          isInFormattedProducts,
          null
        );
      }
      const formattedProductToAdd = {
        sku,
        product_name,
        price,
        attribute: attributeToAdd,
      };
      formattedProducts.push(formattedProductToAdd);
    } else {
      let attributeToAdd = {};
      if (attribute_name) {
        const rawValueName = attributeNameToValueMap[attribute_name];
        const formattedProductIndexToModify = formattedProducts.findIndex(
          (formattedProduct) => formattedProduct.sku === sku
        );
        const formattedProductToModify =
          formattedProducts[formattedProductIndexToModify];
        attributeToAdd = formatAttribute(
          attribute_name,
          dimension_option,
          rawValueName,
          rawProduct,
          isInFormattedProducts,
          formattedProductToModify
        );
        const modifiedFormattedProduct = {
          ...formattedProducts[formattedProductIndexToModify],
          attribute: {
            ...formattedProducts[formattedProductIndexToModify].attribute,
            ...attributeToAdd,
          },
        };
        const newFormattedProducts = [
          ...formattedProducts.slice(0, formattedProductIndexToModify),
          modifiedFormattedProduct,
          ...formattedProducts.slice(formattedProductIndexToModify + 1),
        ];
        formattedProducts = newFormattedProducts;
      }
    }
  });
  return formattedProducts;
};

const navToAddItemPage = (e) => {
  location.href = "/add-product.php";
};

const renderDeleteProductCheckbox = (sku) => {
  return `<input type="checkbox" class="delete-checkbox" name="delete_product[]" value="${sku}">`;
};

const renderProduct = (product) => {
  const { sku, product_name, price, attribute } = product;
  const productElement = document.createElement("div");
  productElement.classList.add("product");
  productElement.innerHTML += `
        <div>${sku}</div>
        <div>${product_name}</div>
        <div>${price} ${currencySymbol}</div>
        `;

  // Add attribute
  if (Object.keys(attribute).length > 0) {
    Object.entries(attribute).map(([key, value]) => {
      const attributeUnit = attributeNameToUnitMap[key];
      if (typeof value === "object") {
        // example of value => dimension: {
        //     height,
        //     width,
        // }
        if (key === "dimension") {
          const formatDimensionValue = (value) => {
            const formattedDimensionValue = [];
            Object.entries(value).map(([key, value]) => {
              formattedDimensionValue[dimensionFormatSequence[key]] = value;
            });
            const joinedFormattedDimensionValue =
              formattedDimensionValue.join(" x ");
            return joinedFormattedDimensionValue;
          };
          const formattedDimensionValue = formatDimensionValue(value);
          productElement.innerHTML += `
                <div>${key}: ${formattedDimensionValue}</div>
                `;
        }
      } else {
        productElement.innerHTML += `
            <div>${key}: ${value} ${attributeUnit}</div>
            `;
      }
    });
  }
  // renderDeleteProductCheckbox at the end of productElement innerHTML
  productElement.innerHTML += renderDeleteProductCheckbox(sku);
  document.getElementById("products").appendChild(productElement);
};

const renderProducts = (formattedProducts, afterModify = false) => {
  if (afterModify) {
    // Remove before adding entire products that is fetched again from database plus the product that is recently added
    document.getElementById("products").innerHTML = "";
  }
  formattedProducts.map((formattedProduct) => {
    renderProduct(formattedProduct);
  });
};

const getAndRenderProducts = (afterModify) => {
  const handleXMLHttpResponse = (parsedData) => {
    const formattedProducts = formatProductObject(parsedData);
    renderProducts(formattedProducts, afterModify);
  };
  requestXMLHttp("GET", null, null, 'getproduct', handleXMLHttpResponse, "parsedData");
};

const deleteProducts = (e) => {
  const handleXMLHttpResponse = () => {
    getAndRenderProducts(true);
  };
  const formData = getFormData('delete-checkbox', 'delete_product[]','checkbox');
  requestXMLHttp(
    "POST",
    null,
    formData,
    "deleteproduct",
    handleXMLHttpResponse
  );
};

window.addEventListener("DOMContentLoaded", () => {
  getAndRenderProducts(false);
});
