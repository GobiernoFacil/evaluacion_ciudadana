<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Pase usted</title>
    <link rel="stylesheet" type="text/css" href="/css/dev.css">
  </head>
  <body>

  <!-- [[   T H E   A P P   ]] -->
  <form name="survey-app">

    <!-- [ THE TITLE ] -->
    <section id="survey-app-title">
      <p>
        <label>Título</label>
        <input type="text" name="survey-title">
      </p>
    </section>

    <!-- [ THE CONTENT CREATOR ] -->
    <section id="survey-app-questions">
      <h2>Preguntas</h2>

      <div id="survey-app-navigation" style="display:none">
        <ul id="survey-navigation-menu"></ul>
        <h4>Reglas</h4>
        <p id="survey-add-navigation-rule"></p>
        <ul id="survey-navigation-rules"></ul>
      </div>

      <p id="survey-add-buttons">
        <a href="#" class="add-question">Agrega pregunta</a> | 
        <a href="#" class="add-text">Agrega texto(HTML)</a>
      </p>
      
      <!-- [ NEW QUESTION FORM ] -->
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
        <p><a id="survey-add-question-btn" href="#">agregar pregunta</a></p>
        <div id="survey-add-options" style="display:none">
          <h5>Opciones</h5>
          <ul>
            <li><input type="text" name="survey-new-question-option"></li>
          </ul>
        </div>
      </div>
      <!-- { NEW QUESTION FORM ENDS } -->


      <!-- [ NEW CONTENT FORM ] -->
      <div id="survey-add-content" style="display:none">
        <p><label>HTML:</label></p>
        <p><textarea name="html"></textarea></p>
        <p><a id="survey-add-content-btn" href="#">agregar contenido</a></p>
      </div>
      <!-- { NEW CONTENT FORM ENS } -->

      <ul id="survey-question-list"></ul>
    </section>
  </form>
  <!-- {{   T H E   A P P   E N D S   }} -->

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