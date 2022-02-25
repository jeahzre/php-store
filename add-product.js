function capitalize(s) {
  return s[0].toUpperCase() + s.slice(1);
}

const navToListPage = () => {
  location.href = "/";
};

const addProduct = (e) => {
  e.preventDefault();
  const handleXMLHttpResponse = (parsedData) => {
    const { errorMessage, successMessage, errorMessagesObject } = parsedData;
    const removeExistingMessage = () => {
      const element = document.getElementById("top-message");
      element.classList.remove("show", "error-message", "success-message");
      element.innerHTML = '';
      const elements = document.querySelectorAll('[id$="-field-message"]');
      elements.forEach((element) => element.remove());
    };
    removeExistingMessage();
    if (errorMessage) {
      const renderErrorMessage = (message) => {
        const element = document.getElementById("top-message");
        element.classList.add("show", "error-message");
        element.innerHTML = message;
      };
      renderErrorMessage(errorMessage);
    }
    if (typeof(errorMessagesObject) === 'object' && errorMessagesObject !== null && Object.keys(errorMessagesObject).length > 0) {
      const renderErrorMessages = (errorMessagesObject) => {
        const createErrorNode = (field, innerHTML) => {
          const errorNode = document.createElement("div");
          errorNode.id = `${field}-field-message`;
          errorNode.classList.add("error-message");
          errorNode.innerHTML = innerHTML;
          return errorNode;
        };
        Object.keys(errorMessagesObject).map((field) => {
          const insertOrReplaceAfter = (refNode, nodeToInsert) => {
            if (document.getElementById(nodeToInsert.id)) {
              refNode.parentNode.replaceChild(
                nodeToInsert,
                refNode.nextSibling
              );
            } else {
              refNode.parentNode.insertBefore(
                nodeToInsert,
                refNode.nextSibling
              );
            }
          };
          const inputNode = document.getElementsByName(field)[0];
          const errorNode = createErrorNode(field, errorMessagesObject[field]);
          insertOrReplaceAfter(inputNode, errorNode);
        });
      };
      renderErrorMessages(errorMessagesObject);
    }
    if (successMessage) {
      const renderSuccessMessage = (message) => {
        const element = document.getElementById("top-message");
        element.classList.add("show", "success-message");
        element.innerHTML = message;
      };
      renderSuccessMessage(successMessage);
      navToListPage();
    }
  };

  const formData = getFormData("add_product");
  requestXMLHttp(
    "POST",
    null,
    formData,
    "addproduct",
    handleXMLHttpResponse,
    "parsedData"
  );
};

function renderProductTypeFormRows(attribute) {
  const attributeForm = document.getElementById("attribute-form");
  attributeForm.innerHTML = "";
  attribute.map((eachAttribute) => {
    renderProductTypeFormRow(eachAttribute, true);
  });
}

function renderProductTypeFormDescription(attributeDescription) {
  const productTypeFormDescriptionElement = document.getElementById('product-type-form-description');
  productTypeFormDescriptionElement.innerHTML = attributeDescription;
}

const setProductTypeForm = (e, init) => {
  const handleXMLHttpResponse = (parsedData) => {
    const { attribute, attributeDescription} = parsedData;
    if (Array.isArray(attribute)) {
      renderProductTypeFormRows(attribute);
    } else {
      renderProductTypeFormRow(attribute, false);
    }
    const productTypeFormDescriptionElement = document.getElementById('product-type-form-description');
    productTypeFormDescriptionElement.innerHTML = '';
    renderProductTypeFormDescription(attributeDescription);
  };

  let type;
  if (e) {
    type = e.target.value;
  } else if (init) {
    const selectElement = document.getElementById("productType");
    type = selectElement.value;
  }

  requestXMLHttp(
    "GET",
    type,
    null,
    "changeproducttypeform",
    handleXMLHttpResponse,
    "parsedData"
  );
};

// On type switcher change
document.getElementById("productType").addEventListener("change", (e) => {
  setProductTypeForm(e);
});

window.addEventListener("DOMContentLoaded", () => {
  setProductTypeForm(null, true);
});
