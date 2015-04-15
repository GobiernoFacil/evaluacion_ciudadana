<div class="container">
	<div class="row">
    <h1 class="title">Administradores</h1>

    <form name="add-admin" method="post">
      <h2>Crear administrador</h2>
      <p><label>correo</label><input type="text" name="email"></p>
      <p><label>contraseña</label><input type="password" name="password"></p>
      <p>Tipo de administrador</p>
      <ul>
        <li><label><input type="radio" name="level" value="1">nivel 1</label></li>
        <li><label><input type="radio" name="level" value="2">nivel 2</label></li>
        <li><label><input type="radio" name="level" value="3">nivel 3</label></li>
        <li><label><input type="radio" name="level" value="4">nivel 5</label></li>
        <li><label><input type="radio" name="level" value="5">jefe de jefes</label></li>
      </ul>
      <p><input type="submit" value="crear administrador"></p>
    </form>

    <section>
      <h2>Administradores</h2>
      <ul>
      <?php foreach($admins as $admin): ?>
        <li>
          <a href="<?= site_url("wackyland/admins/" . $admin->id); ?>">
            <?php echo $admin->email; ?>
          </a>
        </li>
      <?php endforeach; ?>
      </ul>
    </section>
	</div>
</div>