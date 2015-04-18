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
		<?php 
			$i=0;
			foreach($surveys as $survey): 
				// break foreach
				$i++;
				if($i==3) break;
			?>
			<li class="row">
				<div class="col-sm-8">
					<?php echo anchor('resultados/'. $survey->id, $survey->id ==1 ? "Jóvenes Prospera <strong>&gt;</strong>" : $survey->title . " <strong>&gt;</strong>");?>
					
				</div>
				<div class="col-sm-4">
					<span class="data">
						<?php echo anchor('resultados/'. $survey->id, "Descargar datos");?>
						</span>
					<span class="date"><?php echo $survey->creation_date;?></span>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
		</div>
	</section>
</div>