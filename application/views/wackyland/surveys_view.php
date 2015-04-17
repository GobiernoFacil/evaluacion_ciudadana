<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="title">Encuestas</h1>
			<section class="box">
			  	<h2>Crear encuesta</h2>
				<form name="add-survey" method="post" class="row" action="<?= site_url("wackyland/surveys/create"); ?>">
				  <div class="col-sm-1">
				  	<label>Título: </label> 
				  </div>
				  <div class="col-sm-8">
			  	 	  <input type="text" name="title">
				  </div>
				  <div class="col-sm-3">
				 	 <p><input type="submit" value="crear encuesta"></p>
				  </div>
				</form>
			</section>
			
			<section class="box">
			  <h2>Encuestas</h2>
			  <ul class="list">
				  <li class="row los_titles">
				  	 <div class="col-sm-10">
					  	 <h4>Nombre</h4>
				  	 </div>
				  	 <div class="col-sm-2">
					  	  <h4>Acciones</h4>
				  	 </div>
				  </li>
			  <?php foreach($surveys as $survey): ?>
			    <li class="row">
			      <div class="col-sm-10">
			      <a href="<?= site_url("wackyland/surveys/update/" . $survey->id); ?>">
			        <?php echo $survey->title; ?>
			      </a>
			      </div>
			       <div class="col-sm-2">
				   		<a href="<?= site_url("wackyland/surveys/delete/" . $survey->id); ?>" class="danger">Eliminar</a>
			       </div>
			    </li>
			  <?php endforeach; ?>
			  </ul>
			</section>
		</div>
	</div>
</div>