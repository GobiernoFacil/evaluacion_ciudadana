<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="es" class="no-js"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Custionario | Test</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href='http://fonts.googleapis.com/css?family=Raleway:700,400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
	<div class="container cuestiona">
		<h1>Primer formulario</h1>
		<div id="main" class="row">
			<div class="col-sm-12">
				<form id="survey">
		  
		  		</form>
			</div>
		</div>
  </div>
  
  <footer>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<p>Forjado artesanalmente por <a href="http://gobiernofacil.com" class="gobiernofacil">Gobierno Fácil</a></p>
			</div>
			<div class="col-sm-9">
				<ul>
					<li>¿Qué es?</li>					
					<li>Datos abiertos</li>
					<li>Preguntas</li>
					<li>Términos</li>
					<li>Privacidad</li>
					<li>Contacto</li>
				</ul>
			</div>
		</div>
	</div>
	</footer>
  
  <!-- JS STUFF -->
  <script>
  var form_key       = "<?php echo $applicant->form_key; ?>",
      form_title     = "<?php echo $blueprint->title; ?>",
      form_id        = <?php echo $blueprint->id; ?>,
      form_questions = <?php echo json_encode($questions); ?>,
      form_options   = <?php echo json_encode($options); ?>;
  </script>
  <!-- DEVELOPMENT SOURCE -->
  <script data-main="/js/main" src="/js/bower_components/requirejs/require.js"></script>
</body>
</html>