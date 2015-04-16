<div class="container">
	<div class="row">
		<article class="data_hm">
			<div class="col-sm-8 col-sm-offset-2">
				<h1>Resultados de cuestionarios en <strong>Tú Evalúas</strong></h1>
				<?php foreach($response as $survey):?>
				<section>
					<h2><?php echo anchor('resultados/'. $survey->id, $survey->id ==1 ? "Jóvenes Prospera" : $survey->title);?></h2>
					<?php 
						switch($survey->id){
								case 1:
									$figure = "prospera.jpg";
									break;
								case 2: 
									$figure = "inadem.jpg";
									break;
								default:
									$figure = "default.jpg";
						}?>
					
					<?php echo anchor('resultados/'. $survey->id,'<figure>
						<img src="/img/programas/'.$figure.'">
					</figure>');?>
					<p class="lead">
						<?php 
							switch($survey->id){
								case 1:
									$des = "Opiniones sobre Prospera para mejorar la forma en que el programa atiende a los beneficiarios.";
									break;
								case 2: 
									$des = "Cuestionario de satisfacción del Fondo Nacional Emprendedor (INADEM)";
									break;
								default:
									$des = "";
							}
							echo $des;?>
						<br>
						<?php echo anchor('resultados/'.$survey->id,'Consulta los resultados', array('class'=>'btn'));?>
					</p>
				</section>
				<?php endforeach;?>
			</div>			
		</article>
	</div>
</div>