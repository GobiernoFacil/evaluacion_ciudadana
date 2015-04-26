<div class="container dashboard">
	<div class="row">
		<div class="col-md-12">
			<h1 class="title">Dashboard</h1>
		</div>
		<div class="col-sm-4 col-sm-offset-2 box">
			<h3><a href="<?= site_url("wackyland/surveys"); ?>"><strong><?php echo count($surveys);?></strong> 
				<?php echo count($surveys) == 1 ? 'Encuesta' :'Encuestas';?> &gt;</a></a>
			</h3>
			<p> <a href="<?= site_url("wackyland/surveys"); ?>">Crear Encuesta</a></p>
		</div>
		<?php if($user->level >= 5): ?>
		<div class="col-sm-4 col-sm-offset-1 box">
			<h3><a href="<?= site_url("administradores"); ?>"><strong><?php echo count($admins);?></strong> 
				<?php echo count($admins) == 1 ? 'Usuario' :'Usuarios';?> &gt;</a>
			</h3>
			<p> <a href="<?= site_url("administradores"); ?>">Crear Usuario</a></p>
		</div>
	<?php endif; ?>
	</div>
	</div>
</div>