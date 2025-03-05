window.onload = function() {
  //<editor-fold desc="Changeable Configuration Block">

  // the following lines will be replaced by docker/configurator, when it runs in a docker-container

  /* api gen onload */
  fetch('/api-gen').then(function (response) {
    return response.json();
  }).then(function (body) {
        //doSomething with body;
  });

  window.ui = SwaggerUIBundle({
    url: window.location.origin + "/openapi.json",
    dom_id: '#swagger-ui',
    deepLinking: true,
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset
    ],
    plugins: [
      SwaggerUIBundle.plugins.DownloadUrl
    ],
    layout: "StandaloneLayout"
  });

  //</editor-fold>
};
