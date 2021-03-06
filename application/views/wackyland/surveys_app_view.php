<div class="container">
	<div class="row">
		<div class="col-md-12">
		<h1 class="title">Editar Encuesta</h1>
		</div>
	</div>
  <!-- [[   T H E   A P P   ]] -->
  <form name="survey-app">
  <div class="row">
  	
    <!-- [ THE BLUEPRINT ] -->
    <div class="col-sm-4">
      <section id="survey-app-title" class="box">
        <h2>Datos</h2>
        <!-- THE TITLE -->
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1">
            <p><input type="text" name="survey-title"></p>
          </div>
        </div>
        <!-- IS VISIBLE -->
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1">
            <p><label>
              <input type="checkbox" name="is_public">
              Es pública
            </label></p>
          </div>
        </div>
        <!-- IS CLOSED -->
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1">
            <p><label>
              <input type="checkbox" name="is_closed">
              Ya terminó
            </label></p>
          </div>
        </div>
        <!-- CREATE/GET CSV -->
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1">
            
            <!-- GET CSV -->
            <?php if($blueprint->csv_file && $csv_file && $csv_file['size']): ?>
              <p><a href="#" class="create-survey-btn">crear nuevo CSV</a></p>
              <p>
                <a id="get-csv-btn" href="/csv/<?php echo $csv_file['name']; ?>">descargar Archivo (<?php echo date('d-m-Y h:iA', $csv_file['date']); ?>)</a>
              </p>
            <?php elseif($blueprint->csv_file): ?>
              <p><a id="get-csv-btn" href="#">Generando CSV</a></p>
            <?php else: ?>
              <p><a href="#" class="create-survey-btn">crear CSV</a></p>
              <p><a style="display:none" id="get-csv-btn" href="#">[ CSV PLACEHOLDER ]</a></p>
            <?php endif; ?>
          </div>
        </div>

        <!-- PUT YOUR OWN FILE CSV -->
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1">
          <p>Sube tus propios resultados</p>
          <p id="sending-label" style="display:none;">"enviado documento"</p>
          <p id="send-file-button"><input type="file" name="results" id="results-file"></p>
          </div>
        </div>
      </section>
  	</div>
    <!-- { THE BLUEPRINT ENDS } -->
  	

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
          <label><strong>Pregunta:</strong></label>
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
        
        <!-- [4] agrega las respuestas para opción múltiple -->
        <div id="survey-add-options" style="display:none">
          <h4>Opciones de respuesta:</h4>
          <p>Presiona [ ENTER ] para agregar otra respuesta</p>
          <ul>
          </ul>
          <p>
            <label>
              <input type="checkbox" id="keep-options" value="1">
              conservar respuestas
            </label>
          </p>
        </div>
        <!-- [4] -->
        
        <!-- [3] define a la sección a la que pertenece la pregunta -->
        
        <div class="survey-section-selector-container"><!-- weird hack -->
        <p class="survey-section-selector" style="display:none">
          <label><strong>Sección a la que pertenece:</strong>
          <select name="section_id">
            <option value="1" selected>sección 1</option>
            <option value="0">nueva sección</option>
          </select></label>
        </p>
        </div><!-- weird hack ends -->
        <!-- [3] -->
        
        <!-- [5] salva la pregunta -->
        <p><a id="survey-add-question-btn" href="#" class="btn_add">agregar</a></p>
        <!-- [5] -->
      </div>
      <!-- { NEW QUESTION FORM ENDS } -->


      <!-- [ NEW CONTENT FORM ] -->
      <div id="survey-add-content" style="display:none">
        <p><label>HTML:</label></p>
        <p><textarea name="html"></textarea></p>
        <div class="survey-section-selector-container"><!-- weird hack -->
        </div><!-- weird hack ends -->
        <p><a id="survey-add-content-btn" href="#" class="btn_add">Agregar contenido</a></p>
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
      
      <!-- [ THE SECTION NAVIGATION ] -->
      <div id="survey-app-navigation" style="display:none">
        <ul id="survey-navigation-menu"></ul>
        

        <!-- [ THE RULES NAVIGATION ] -->
        <div id="survey-navigation-rules-container" style="display:none">
          <h5>Reglas de navegación de la sección</h5>
          <p class="instructions">Selecciona una pregunta y su respuesta para determinar que usuarios verán esta sección</p>
          <p id="survey-add-navigation-rule">
            <select class="select-question">
              <option value="">Selecciona una pregunta</option>
            </select>

            <select class="select-answer" style="display:none">
              <option value="">Selecciona una respuesta</option>
            </select>

            <a href="#" class="add-rule-btn btn_add">Agregar Regla</a>
          </p>
          <ul id="survey-navigation-rules" start="1"></ul>
        </div>


      </div>
      
      <ol id="survey-question-list" start="1"></ol> 
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
        questions : <?= json_encode($questions); ?>,
        options   : <?= json_encode($options); ?>,
        rules     : <?= json_encode($rules); ?>
      };

    </script>
    <!-- DEVELOPMENT SOURCE -->
    <script data-main="/js/main.admin" src="/js/bower_components/requirejs/require.js"></script>
