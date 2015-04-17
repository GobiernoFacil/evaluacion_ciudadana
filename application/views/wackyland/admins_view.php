<div class="container">
	<div class="row">
    	<h1 class="title">Administradores</h1>
		<div class="row">
			<!-- add users-->
			<div class="col-sm-4">	
				<section class="box">
					<form name="add-admin" method="post" class="row">
					  <h2>Crear administrador</h2>
					  <div class="col-sm-12">
					  <p><label>correo</label><input type="text" name="email"></p>
					  <p><label>contraseña</label><input type="password" name="password"></p>
					  <p>Tipo de administrador</p>
					  <ul class="options">
					    <li><label><input type="radio" name="level" value="1">nivel 1</label></li>
					    <li><label><input type="radio" name="level" value="2">nivel 2</label></li>
					    <li><label><input type="radio" name="level" value="3">nivel 3</label></li>
					    <li><label><input type="radio" name="level" value="4">nivel 5</label></li>
					    <li><label><input type="radio" name="level" value="5">jefe de jefes</label></li>
					  </ul>
					  <p><input type="submit" value="crear administrador"></p>
					  </div>
					</form>
				</section>
			</div>
			<!--  users list-->
			<div class="col-sm-8">	
				<section class="box">
				  <h2>Administradores</h2>
				  <h3>Total de Usuarios
						  <strong><?php echo count($admins);?></strong>
					  </h3>
				   <ul class="list">
				   	<li class="row los_titles">
				   	   <div class="col-sm-8">
				   	    	 <h4>Correo</h4>
				   	   </div>
				   	   <div class="col-sm-4">
				   	    	  <h4>Tipo</h4>
				   	   </div>
				   	</li>

				  <?php foreach($admins as $admin): ?>
				    <li class="row">
				    	<div class="col-sm-8">
				    	 <a href="<?= site_url("wackyland/admins/" . $admin->id); ?>">
				    	   <?php echo $admin->email; ?>
				    	 </a>
				    	</div>
				    	<div class="col-sm-4">
				    	   <?php echo $admin->level; ?>
				    	</div>
				    </li>
				  <?php endforeach; ?>
				  </ul>
				</section>
			</div>
		</div>
	</div>
</div>