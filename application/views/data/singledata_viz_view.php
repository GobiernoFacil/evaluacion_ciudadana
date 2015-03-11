<div class="container">
	<div class="row">
		<article class="data_hm">
			<div class="col-sm-8 col-sm-offset-2">
				<h1>Resultados de Cuestionario 1, <strong>Prospera</strong></h1>
				<section>
					<h2>Participantes: <strong><?php echo $response['applicants'];?></strong></h3>

				</section>
				<div>
					<h2>Respuestas</h2>
					<ol>
					<?php foreach($response['questions'] as $question):?>
						<li>
							<h3><?php	echo $question->question;?></h3>
							<?php if(empty($question->options)):?>
								<?php foreach($question->answers as $respuesta):?>
									<p>
									<?php echo $respuesta->num_value;?>
									</p>
								<?php endforeach;?>
							<?php else:?>
								<ul>
								<?php foreach($question->options as $respuesta):?>
									<li>
									<?php echo '<strong>' . $respuesta->answer_num . '</strong>: ' . $respuesta->description;?>
									</li>
								<?php endforeach;?>
										
								</ul>
							<?php endif;?>					
						</li>
					<?php endforeach;?>
					</ol>
	 			</div>
			</div>			
		</article>
	</div>
</div>