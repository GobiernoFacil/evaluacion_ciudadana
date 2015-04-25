<div class="container">
	<div class="row">
    	<h1 class="title">Administradores</h1>
		<div class="row">
			<!-- add users-->
			<div class="col-sm-4">	
				<section class="box">

					<form name="add-admin" method="post" class="row" id="add-admin-form">
					  <h2>Crear administrador</h2>
					  <div class="col-sm-12">
					  <p>
            <!--
            return ['success'  => false, 
              'email'    => $email, 
              'password' => $password, 
              'exist'    => $exist, 
              'level'    => $level];
            -->
              <!-- [ ERROR MESSAGE ] -->
              <strong id="error-message">
              <?php if($report && !$report['success']): ?>
                <?php if(! $report['email']) : ?>
                  el correo no es válido O___O
                <?php elseif(! $report['password']) : ?>
                  la contraseña debe contener por lo menos ocho caracteres
                <?php elseif($report['exist']) : ?>
                  El usuario ya existe :/
                <?php endif; ?>
              <?php endif; ?>
              </strong>

            </p>
					  <p><label>correo</label><input id="the-new-email" type="text" name="email" value=""></p>
					  <p><label>contraseña</label><input id="the-new-pass" type="password" name="password" value=""></p>
					  <p>Tipo de administrador</p>
					  <ul class="options">
					    <li><label><input type="radio" name="level" value="1" checked="checked">curioso</label></li>
					    <li><label><input type="radio" name="level" value="3">funcionario</label></li>
					    <li><label><input type="radio" name="level" value="5">administrador</label></li>
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
				    	 <a href="<?= site_url("administradores/" . $admin->id); ?>">
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
<script>
  // more crapy validation
  var form = document.getElementById('add-admin-form'),
      error = document.getElementById('error-message');

  form.onsubmit = function(e){
    e.preventDefault();
    var email = document.getElementById('the-new-email'),
        pass  = document.getElementById('the-new-pass');

    email.className = '';
    pass.className = '';
    error.innerHTML = "";

    if(! email.value){
      email.className = 'error'; 
      error.innerHTML = "el campo de correo está vacío :(";
      return;
    }
    if(pass.value.length < 8){
      pass.className = 'error'; 
      error.innerHTML = "mínimo 8 caracteres para la contraseña por favor!";
      return;
    } 
   
    this.submit();
  }






</script>