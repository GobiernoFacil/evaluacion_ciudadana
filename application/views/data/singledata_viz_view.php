<div class="container">
	<div class="row">
		<article class="data_hm">
			<div class="col-sm-8 col-sm-offset-2">
				<h1>Resultados de Cuestionario <strong>Jóvenes Prospera</strong></h1>
				<section class="row">
			<!--	<h3 class="col-sm-6" >Participantes: <strong><?php echo $response['applicants'];?></strong></h3>-->
					<?php echo anchor("resultados/1/csv", 'Descargar datos en CSV', array("class"=>"btn col-sm-5"));?>
					<?php echo anchor("resultados/1/archivo", 'Descargar datos en JSON', array("class"=>"btn col-sm-5 col-sm-offset-1"));?>
				</section>
				<div class="answers">
					<h2>Respuestas</h2>
					<?php 
						// buscamos totales
						$array_answer=array();
						foreach($response['questions'] as $question):?>
						<?php 
							$total_answer_num = 0;
							// los sacamos de fea manera
							foreach($question->options as $respuesta):?>
								<?php 
									//si es string
									if (is_string($respuesta->answer_num)) 
									{
										$number = (int)$respuesta->answer_num;
										$float  = (float)$number;
										$total_answer_num = $total_answer_num + $float;
									}
									else 
									{
										$total_answer_num = $total_answer_num + $respuesta->answer_num;
									}
								?>
						<?php endforeach;
							// clavamos total y id al array
							$array_answer[] = array(
											"id" 		=> $question->id,
											"total_num" => $total_answer_num,
							);
						?>	
						<?php endforeach;?>
					<!-- comienza lista de preguntas-->
					<ol>
					<?php foreach($response['questions'] as $question):?>
						<li >
							<h3><?php	echo $question->question;?></h3>
							<?php 
								// si no hay opciones
								if(empty($question->options)):?>
								<?php foreach($question->answers as $respuesta):?>
									<p>
									<?php echo $respuesta->num_value;?>
									</p>
								<?php endforeach;?>
							<?php 
								// si hay opciones
								else:?>
								<ul class="row">
								<?php foreach($question->options as $respuesta):?>
								<span class="clearfix">
									<li class="col-sm-6">
									<?php 
										/// buscamos el total de respuestas de la pregunta
										$le_total = 0;
										foreach ($array_answer as $los_totales):?>
										<?php 											
											if ($los_totales['id'] == $question->id) {
												$le_total = $los_totales['total_num'];		
											}
										?>
									<?php endforeach;?>
									<?php 
										///calcula porcentaje de respuestas… sad thing
										if ($le_total > 0) {
											$amount =  ($respuesta->answer_num / $le_total) * 100;																			$amount = round($amount, 2);
										}
										else {
											$amount = 0;
										}
										echo  $respuesta->description . ': <strong>' . $amount . '%</strong> 
												<span class="total">('.$respuesta->answer_num.')</span>' ;
									?>
									</li>
									<li class="col-sm-6">
										<span class="the_bar"> 
										<span class="bar" style="width:<?php echo $amount;?>%"></span>
										</span>
									</li>
								</span>
								<?php endforeach;?>
										
								</ul>
							<?php endif;?>					
						</li>
					<?php endforeach;?>
					</ol>
	 			</div>
				<p><?php echo anchor("resultados/1/csv", 'Descargar datos en CSV', array("class"=>"btn"));?></p>
				<p><?php echo anchor("resultados/1/archivo", 'Descargar datos en JSON', array("class"=>"btn"));?></p>
			</div>			
		</article>
	</div>
</div>