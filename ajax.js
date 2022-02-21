function isJSON(str) {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  }
  return true;
}

function requestXMLHttp(
  method,
  query,
  formData,
  serverFileName,
  handleXMLHttpResponse,
  handleXMLHttpResponseArguments
) {
  const xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      let parsedData = null;
      if (isJSON(this.responseText)) {
        parsedData = JSON.parse(this.responseText);
      } 
      // else {
      //   document.body.innerHTML += this.responseText;
      // }
      if (parsedData) {
        if (
          handleXMLHttpResponseArguments &&
          handleXMLHttpResponseArguments === "parsedData"
        ) {
          handleXMLHttpResponse(parsedData);
        } else {
          handleXMLHttpResponse();
        }
      }
    }
  };
  let filePathAndQuery = "";
  if (method === "GET" && query) {
    filePathAndQuery = `/api/${serverFileName}.php?q=${query}`;
  } else {
    filePathAndQuery = `/api/${serverFileName}.php`;
  }
  xmlhttp.open(method, filePathAndQuery);
  xmlhttp.send(formData);
}
