<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="title">Encuestas</h1>
			<div class="row">
				<!-- add survey-->
				<div class="col-sm-4">
					<section class="box">
			  		<h2>Crear encuesta</h2>
					<form name="add-survey" method="post" class="row" action="<?= site_url("wackyland/surveys/create"); ?>">
					  <div class="col-sm-12">
					  	<p><label>Título: </label> 
						  	<input type="text" name="title">
					  	</p>
					  </div>
					  <div class="col-sm-12">
					 	 <p><input type="submit" value="crear encuesta"></p>
					  </div>
					</form>
					</section>
				</div>
				
				<!-- survey list-->
				<div class="col-sm-8">
					<section class="box">
					  <h2>Encuestas</h2>
					  <h3>Total de Encuestas
						  <strong><?php echo count($surveys);?></strong>
					  </h3>
					  
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
				
			</div><!--- ends row-->
		</div>
	</div>
</div>