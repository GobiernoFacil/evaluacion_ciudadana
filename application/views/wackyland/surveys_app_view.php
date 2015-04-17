<div class="container">
	<div class="row">
		<div class="col-md-12">
		<h1 class="title">Editar Encuesta</h1>
		</div>
	</div>
  <!-- [[   T H E   A P P   ]] -->
  <form name="survey-app">
  <div class="row">
  	
    <!-- [ THE TITLE ] -->
    <div class="col-sm-4">
    <section id="survey-app-title" class="box">
    	<h2>Título</h2>
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<p>
				  <input type="text" name="survey-title">
				</p>
      		</div>
        </div>
    </section>
  	</div>
  	
  	<div class="col-sm-8">
	  	
    <!-- [ THE CONTENT CREATOR ] -->
    <section id="survey-app-questions" class="box">
    	<h2>Agregar preguntas</h2>
		<!-- [ ADD CONTENT BUTTONS ] -->
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
		<p id="survey-add-buttons">
		  <a href="#" class="add-question">Agrega pregunta</a> | 
		  <a href="#" class="add-text">Agrega texto(HTML)</a>
		</p>
      
      <!-- [ NEW QUESTION FORM ] -->
      <div id="survey-add-question" class="new_question" style="display:none">
        <!-- [1] agrega el título -->
        <p>
          <label>Pregunta:</label>
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
        <p><a id="survey-add-question-btn" href="#" class="btn_add">agregar</a></p>
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
      		</div>
        </div>
    </section>
    <!-- { THE CONTENT CREATOR ENDS } -->

    <!-- [ THE SURVEY ] -->
    <section id="the-survey" class="box">
	    <h2>Preguntas agregadas</h2>
	    <div class="row">
			<div class="col-sm-10 col-sm-offset-1">
      <div id="survey-app-navigation" style="display:none">
        <ul id="survey-navigation-menu"></ul>
        <h4>Reglas</h4>
        <p id="survey-add-navigation-rule"></p>
        <ul id="survey-navigation-rules"></ul>
      </div>
      
      	<ol id="survey-question-list" ></ol> 
			</div>
	    </div>
    </section>
    <!-- { THE SURVEY ENDS } -->
    
  	</div>
  	</div>
  </form>
  <!-- {{   T H E   A P P   E N D S   }} -->
	
</div>
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
