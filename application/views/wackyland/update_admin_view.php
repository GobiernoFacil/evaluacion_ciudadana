<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>usuario</title>
</head>
<body>
  <h1>Editar usuario</h1>
  <form name="add-admin" method="post" class="row" id="add-admin-form">
  
              <strong id="error-message">
              <?php if($report && !$report['success']): ?>
                <?php if(! $report['password']) : ?>
                  sepa qué tranza con el password
                <?php elseif(! $report['pass_len']) : ?>
                  El nuevo password debe tener por lo menos ocho caracteres
                <?php endif; ?>
              <?php endif; ?>
              </strong>

            </p>
            <p>correo: <em><?php echo $user->email; ?></em></p>
            <p><label>contraseña</label><input id="the-new-pass" type="password" name="password" value=""></p>

            <?php if($user->level >= '5'): ?>
            <p>Tipo de administrador</p>
            <ul class="options">
              <li><label>
              <input type="radio" name="level" value="1" <?php echo $user->level == '1' ? 'checked="checked"' : ''; ?>>curioso
              </label></li>
              <li><label>
              <input type="radio" name="level" value="3" <?php echo $user->level == '3' ? 'checked="checked"' : ''; ?>>funcionario
              </label></li>
              <li><label>
              <input type="radio" name="level" value="5" <?php echo $user->level >= '5' ? 'checked="checked"' : ''; ?>>administrador
              </label></li>
            </ul>
            <?php endif; ?>
            
            <p><label>mailgun apikey</label><input name="mailgun" value=""></p>
            <p><input type="submit" value="editar"></p>
            </div>
  </form>
</body>
</html>