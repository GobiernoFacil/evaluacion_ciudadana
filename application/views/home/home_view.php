<div class="container">
	<header class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<h1 class="tuevaluas">Tú Evalúas</h1>
			<h2 class="intro">Tu opinión sobre los programas públicos federales ayuda a mejorarlos.</h2>
			<p>Si recibes un correo con la invitación,<br>
				¡<strong>participa</strong>, eres muy importante!</p>
		</div>
	</header>
	<section class="programs row">
		<div class="col-sm-10 col-sm-offset-1">
		<h2>Programas Evaluados</h2>
		</div>
		<div class="col-sm-8 col-sm-offset-2">
		<ul>
		<?php foreach($surveys as $survey): ?>
			<li class="row">
				<div class="col-sm-8">
					<?php echo anchor('resultados/'. $survey->id, $survey->title . " <strong>&gt;</strong>");?>
					
				</div>
				<div class="col-sm-4">
					<?php
					  // revisa si existe el CSV
					  $csv  = $survey->csv_file;
					  $path = $csv ? $csv_path . $csv : false;
					  $file = $path ? get_file_info($path) : false;
					  $url  = $file && $file['size'] ? "/csv/{$csv}" : false;
					?>
					<?php if($url): ?>
					<!-- si existe el CSV, muestra el link -->
					<span class="data">
						<a href="<?php echo $url; ?>">Descargar datos</a>
						</span>
					<span class="date"><?php echo date('d-m-Y h:iA', $file['date']); ?></span>
				<?php endif; ?>

				</div>
			</li>
		<?php endforeach; ?>
		</ul>
		</div>
	</section>
</div>