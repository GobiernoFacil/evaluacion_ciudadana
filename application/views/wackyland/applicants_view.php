<div class="container">
	<div class="row">
    	<h1 class="title">Cuestionarios públicos</h1>
		<div class="row">
  <?php if(empty($blueprints)): ?>
  			<div class="col-sm-4">	
				<section class="box">
					<!-- No hay ninguna encuesta -->
					<p>No tienes ninguna encuesta pública; 
						<a href="/index.php/bienvenido/encuestas">crea o publica una.</a></p>
				</section>
  			</div>
  
  <?php else: ?>
  <?php $base_path = "/index.php/bienvenido/cuestionarios"; ?>
  		<div class="col-sm-8 col-sm-offset-2">	
				<section class="box cuestionario">
					<!-- hay aplicaciones -->
					<ul>
					  <?php foreach ($blueprints as $bp): ?>
					  <li>
					  <h2><?= $bp->title; ?> (<?= number_format($bp->applicants) . ' de ' . number_format($max_app); ?>)</h2>
					  <!-- [A] envía una a algún correo -->
					  <form id="mail-to-<?=$bp->id;?>" action="<?="{$base_path}/mail_to/{$bp->id}";?>" method="post" class="col-sm-12">
					    <h3>Envía formulario a un correo</h3>
					    <div class="col-sm-10">
						    <input name="email" type="text"> 
					    </div>
					    <div class="col-sm-2">
						 <input type="submit" value="enviar">
					     </div>
					  </form>

					  <!-- [B] genera nuevos cuestionsarios -->
					  <form id="new-num-<?=$bp->id;?>" action="<?="{$base_path}/new_num/{$bp->id}";?>" method="post" class="col-sm-12">
					    <h3>Genera nuevos cuestionarios</h3>
					    <p>
					      <label>escribe el número de cuestionarios por crear:</label>
					    </p>
					    <div class="col-sm-10">
					      <input type="text" name="cuestionarios" value="0">
					    </div>
					    <div class="col-sm-2">
					      <input type="submit" value="crear">
					    </div>
					  </form>
					
					  <!-- [C] genera nuevos cuestionarios en base a correos -->
					  <form id="new-file-<?=$bp->id;?>" action="<?="{$base_path}/new_file/{$bp->id}";?>" method="post" enctype="multipart/form-data" class="col-sm-12">
					    <h3>Genera nuevos cuestionarios mediante un CSV con correos</h3>
					    <div class="col-sm-5">
						    <input type="file" name="csv">
					    </div>
					    <div class="col-sm-7">
					    	<input type="submit" value="subir archivo">
					    </div>
					  </form>
					  
					  <div class="col-sm-12">
					    <h3>Descargar:</h3>
					    <!-- [D] descarga los cuestionarios y correos en CSV -->
					    <p><a href="<?="{$base_path}/get_all/{$bp->id}";?>">descarga en CSV el id y correo de los cuestionarios</a></p>
					  
					    <!-- [E] descarga los correos -->
					    <p><a href="<?="{$base_path}/get_emails/{$bp->id}";?>">descarga en CSV los correos para 
					    respaldo o para utilizarlos en otras encuestas</a></p>
					  
					    <!-- [F] elimina las aplicaciones -->
					    <p><a href="<?="{$base_path}/delete/{$bp->id}";?>">Elimina los cuestionarios. Esto solo borra el acceso a
					    cada cuestionario, pero las respuestas de los cuestionarios ya contestados se mantienen.</a></p>
					  </div>

					  <div class="col-sm-12">
					    <h3>Envío masivo:</h3>
					    <!-- [D] descarga los cuestionarios y correos en CSV -->
					    <p><a href="<?="{$base_path}/send_all/{$bp->id}";?>">Enviar el cuestionario a todos los correos registrados</a></p>
					  </div>

					  <div class="clearfix"></div>
					  </li>
					  <?php endforeach; ?>
					</ul>
				</section>
  		</div>
  <?php endif; ?>
		</div>
	</div>
</div>