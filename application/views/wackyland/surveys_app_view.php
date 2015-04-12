<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Pase usted</title>
    <link rel="stylesheet" type="text/css" href="/css/dev.css">
  </head>
  <body>

  <!-- [ THE APP ] -->
  <form name="survey-app">

  <!-- [ THE TITLE ] -->
    <section id="survey-app-title">
      <p>
        <label>Título</label>
        <input type="text" name="survey-title">
      </p>
    </section>

  <!-- [ THE NAV CREATOR ] -->
    <section id="survey-app-navigation">
  <!-- [2] lista de secciones -->
      <ul id="survey-navigation-menu"></ul>
  <!-- [4] agrega las reglas de navegación 
      <h4>Reglas</h4>
      <p id="survey-add-navigation-rule"></p>-->
  <!-- [5] lista de reglas de navegación
      <ul id="survey-navigation-rules"></ul>
    </section>

  <!-- [ THE CONTENT LIST ] -->
    <section id="survey-app-questions">
      <h2>Preguntas</h2>
      <p><a href="#">Agrega pregunta</a> | <a href="#">Agrega texto(HTML)</a></p>
      <div id="survey-add-question" style="display:none">
        <p>
        <label>
          <input type="checkbox" name="is_location" value="1">
          Captura la ubicación (Estado, municipio, localidad)
        </label>
        </p>
        <p>
          <label>pregunta:</label>
          <input name="question" type="text">
        </p>
        <p>
          <label>la respuesta es:</label>
          <label><input type="radio" name="type" value="text">abierta</label>
          <label><input type="radio" name="type" value="number">numérica</label>
          <label><input type="radio" name="type" value="multiple">opción múltiple</label>
        </p>
        <div>
          <h5>Opciones</h5>
          <ul>
            <li><input type="text" name="survey-new-question-option"></li>
          </ul>
        </div>
      </div>
      <ul id="survey-question-list"></ul>
    </section>
  </form>

    <script>
      var SurveySettings = {
        blueprint : <?= json_encode($blueprint); ?>,
        sections  : <?= json_encode($sections); ?>,
        questions : <?= json_encode($questions); ?>,
        options   : <?= json_encode($options); ?>,
        rules     : <?= json_encode($rules); ?>
      };
    </script>
    <!-- DEVELOPMENT SOURCE -->
    <script data-main="/js/main.admin" src="/js/bower_components/requirejs/require.js"></script>
  </body>
</html>