<div class="container">
	<div class="row">
		<article>
		<div class="col-sm-8 col-sm-offset-2">
			<h1>Envía un mensaje a la plataforma <strong>Tú Evalúas</strong></h1>
			<?php echo validation_errors('<p class="error">', '</p>'); ?>

			<?php echo form_open(current_url(), array('class'=>'contact_form'));?>
				<p><?php echo form_label('Nombre: ', 'name');?></p>
				<p>	<?php echo form_input('name', set_value('name'));?></p>
				<p><?php echo form_label('Email: ', 'email');?></p>
				<p><?php echo form_input('email', set_value('email'));?></p>
				
				<p><?php echo form_label('Mensaje: ', 'message');?></p>
				<p><textarea name='message'><?php echo set_value("message");?></textarea></p>
				<p><?php echo form_submit('submit', 'Enviar Mensaje') ;?></p>
			<?php echo form_close();?>
			
		</div>
		</article>
	</div>
</div>