<!doctype html>
<html>
<head></head>
<body>
  <h1>Editar usuario</h1>
  <form name="add-admin" method="post" class="row" id="add-admin-form">
  
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
            <p>correo: <!-- --></p>
            <p><label>contraseña</label><input id="the-new-pass" type="password" name="password" value=""></p>

            <p>Tipo de administrador</p>

            <ul class="options">
              <li><label><input type="radio" name="level" value="1" checked="checked">curioso</label></li>
              <li><label><input type="radio" name="level" value="3">funcionario</label></li>
              <li><label><input type="radio" name="level" value="5">administrador</label></li>
            </ul>
            
            <p><label>mailgun apikey</label><input name="mailgun" value=""></p>
            <p><input type="submit" value="editar administrador"></p>
            </div>
  </form>
</body>
</html>