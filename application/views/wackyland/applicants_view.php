<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Encuestas por aplicar</title>
</head>
<body>
  <h1>Aplicaciones</h1>
  <?php if(empty($blueprints)): ?>
  <!-- No hay ninguna encuesta -->
  <p>No tienes ninguna encuesta pública; 
  <a href="/index.php/bienvenido/encuestas">crea o publica una.</a></p>
  <?php else: ?>
  <?php $base_path = "/index.php/bienvenido/cuestionarios"; ?>
  <!-- hay aplicaciones -->
  <ul>
    <?php foreach ($blueprints as $bp): ?>
    <li>
    <h2><?= $bp->title; ?></h2>
    <!-- [A] envía una a algún correo -->
    <form id="mail-to-<?=$bp->id;?>" action="<?="{$base_path}/mail_to/{$bp->id}";?>" method="post">
    </form>

    <!-- [B] genera nuevos cuestionsarios -->
    <form id="new-num-<?=$bp->id;?>" action="<?="{$base_path}/new_num/{$bp->id}";?>" method="post">
    </form>

    <!-- [C] genera nuevos cuestionarios en base a correos -->
    <form id="new-file-<?=$bp->id;?>" action="<?="{$base_path}/new_file/{$bp->id}";?>" method="post" enctype="multipart/form-data">
    </form>
    
    <!-- [D] descarga los cuestionarios y correos en CSV -->
    <p><a href="<?="{$base_path}/get_all/{$bp->id}";?>">descarga en CSV el id y correo de los cuestionarios</a></p>
    
    <!-- [E] descarga los correos -->
    <p><a href="<?="{$base_path}/get_emails/{$bp->id}";?>">descarga en CSV los correos para 
    respaldo o para utilizarlos en otras encuestas</a></p>
    
    <!-- [F] elimina las aplicaciones -->
    <p><a href="<?="{$base_path}/delete/{$bp->id}";?>">Elimina las encuestas. Esto solo borra el acceso a
    cada cuestionario, pero las respuestas de los cuestionarios ya contestados se mantienen.</a></p>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</body>
</html>