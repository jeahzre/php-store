function getFormData(elementClass, elementName, inputType) {
  // elementName -> custom
  const formData = new FormData();
  const elements = document.getElementsByClassName(elementClass);
  if (inputType === "checkbox") {
    Array.from(elements).map((element) => {
      if (element.checked) {
        formData.append(elementName, element.value);
      }
    });
  } else {
    Array.from(elements).map((element) => {
      formData.append(element.name, element.value);
    });
  }

  return formData;
}
