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
      <!-- [ ADD CONTENT BUTTONS ] -->
      <p id="survey-add-buttons">
        <a href="#" class="add-question">Agrega pregunta</a> | 
        <a href="#" class="add-text">Agrega texto(HTML)</a>
      </p>
      
      <!-- [ NEW QUESTION FORM ] -->
      <div id="survey-add-question" style="display:none">
        <!-- [1] agrega el título -->
        <p>
          <label>pregunta:</label>
          <input name="question" type="text">
        </p>
        <!-- [1] --> 
        <!-- [2] define el tipo de pregunta -->
        <p>
          <label>la respuesta es:</label>
          <label><input type="radio" name="type" value="text">abierta</label>
          <label><input type="radio" name="type" value="number">numérica</label>
          <label><input type="radio" name="type" value="multiple">opción múltiple</label>
          <label><input type="radio" name="type" value="location">ubicación</label>
        </p>
        <!-- [2] -->
        <!-- [3] define a la sección a la que pertenece la pregunta -->
        <p id="survey-section-selector" style="display:none">
          <label>sección</label>
          <select name="section_id">
            <option value="1" selected>sección 1</option>
            <option value="0">nueva sección</option>
          </select>
        </p>
        <!-- [3] -->
        <!-- [4] agrega las respuestas para opción múltiple -->
        <div id="survey-add-options" style="display:none">
          <h5>Respuestas:</h5>
          <ul>
          </ul>
        </div>
        <!-- [4] -->
        <!-- [5] salva la pregunta -->
        <p><a id="survey-add-question-btn" href="#">agregar</a></p>
        <!-- [5] -->
      </div>
      <!-- { NEW QUESTION FORM ENDS } -->


      <!-- [ NEW CONTENT FORM ] -->
      <div id="survey-add-content" style="display:none">
        <p><label>HTML:</label></p>
        <p><textarea name="html"></textarea></p>
        <p><a id="survey-add-content-btn" href="#">agregar contenido</a></p>
      </div>
      <!-- { NEW CONTENT FORM ENDS } -->
    </section>
    <!-- { THE CONTENT CREATOR ENDS } -->

    <!-- [ THE SURVEY ] -->
    <section id="the-survey">
      <div id="survey-app-navigation" style="display:none">
        <ul id="survey-navigation-menu"></ul>
        <h4>Reglas</h4>
        <p id="survey-add-navigation-rule"></p>
        <ul id="survey-navigation-rules"></ul>
      </div>
      
      <ul id="survey-question-list"></ul> 
    </section>
    <!-- { THE SURVEY ENDS } -->

  </form>
  <!-- {{   T H E   A P P   E N D S   }} -->

    <!-- THE INITIAL DATA -->
    <script>
      var SurveySettings = {
        blueprint : <?= json_encode($blueprint); ?>,
        sections  : <?= json_encode($sections); ?>,
        questions : <?= json_encode($questions); ?>,
        options   : <?= json_encode($options); ?>,
        rules     : []<?php /* json_encode($rules); */ ?>
      };

    </script>
    <!-- DEVELOPMENT SOURCE -->
    <script data-main="/js/main.admin" src="/js/bower_components/requirejs/require.js"></script>
  </body>
</html>