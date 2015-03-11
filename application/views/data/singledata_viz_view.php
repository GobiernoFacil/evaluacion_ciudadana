<div class="container">
	<div class="row">
		<article class="data_hm">
			<div class="col-sm-8 col-sm-offset-2">
				<h1>Resultados de Cuestionario 1, <strong>Prospera</strong></h1>
				<section>
					<h2>Participantes: <strong><?php echo $response['applicants'];?></strong></h3>

				</section>
			
				<?php foreach($response['questions'] as $question):?>
					<div>
						<h3><?php	echo $question->question;?></h3>
											
					</div>
					<?php 
						echo "<pre>";var_dump($question);
						echo "</pre>";?>
	 			<?php endforeach;?>
				
				<div>
					<h5>Preguntas</h5>
				</div>
			<?php	echo "<pre>";
	 	  var_dump($response);
	 	  echo "</pre>";?>
			</div>			
		</article>
	</div>
</div>