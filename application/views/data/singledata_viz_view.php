<div class="container">
	<div class="row">
		<article class="data_hm">
			<div class="col-sm-8 col-sm-offset-2">
				<h1>Resultados de Cuestionario 1, <strong>Prospera</strong></h1>
				<section class="row">
				<h3 class="col-sm-6" >Participantes: <strong><?php echo $response['applicants'];?></strong></h3>
				<?php echo anchor("datos/1/archivo", 'Descargar datos', array("class"=>"btn col-sm-3"));?>
				</section>
				<div class="answers">
					<h2>Respuestas</h2>
					<ol>
						
					<?php foreach($response['questions'] as $question):?>
						<li >
							<h3><?php	echo $question->question;?></h3>
							<?php if(empty($question->options)):?>
								<?php foreach($question->answers as $respuesta):?>
									<p>
									<?php echo $respuesta->num_value;?>
									</p>
								<?php endforeach;?>
							<?php else:?>
								<ul class="row">

								<?php foreach($question->options as $respuesta):?>
								<span class="clearfix">
									<li class="col-sm-4">
									<?php echo  $respuesta->description . ': <strong>' . $respuesta->answer_num . '</strong>';?>
									</li>
									<li class="col-sm-8">
										
										<?php 
											
											$amount = $respuesta->answer_num / $response['applicants'];
											switch($amount) {
											case 0:
												$tha_class = 0;
												break;
											case $amount <= 0.25:
												$tha_class = 25;
												break;
											case $amount <= 0.5:
												$tha_class = 25;
												break;
											case $amount <= 0.75:
												$tha_class = 75;
												break;
											default:
												$tha_class = 100;
												break;
										};?>
										<span class="bar a<?php echo $tha_class;?>"></span>
									</li>
								</span>
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