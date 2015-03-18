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
    <link rel="shortcut icon" href="/img/favicon.ico">
	<link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<header class="pg">
	<div class="clearfix">
		<nav class="col-sm-3 col-sm-offset-1">
			<a href="/" class="tuevaluas">Tú evalúas</a>
		</nav>
		
	</div>	
</header>
	

	<div class="container cuestiona">
		<h1>Jóvenes Prospera</h1>
		<div id="main" class="row">
			<div class="col-sm-12">
				<form id="survey">
		  
		  		</form>
			</div>
		</div>
  </div>
  
<footer>
	<div class="container">
		<div class="row integrantes">
			<div class="col-sm-10 col-sm-offset-1">
			<h3>Equipo <strong>Tú Evalúas</strong></h3>
			<ul class="row">
				<li class="col-xs-4"><span class="presidencia">Presidencia</span></li>
				<li class="col-xs-4"><a href="http://www.presidencia.gob.mx/edn/" class="mx_digital">Estrategia Digital</a></li>
				<li class="col-xs-4"><span class="shcp">SHCP</span></li>
				<li class="col-xs-4"><a href="http://www.transparenciapresupuestaria.gob.mx/" class="transparencia">Transparencia Presupuestaria</a></li>
				<li class="col-xs-4"><a href="http://www.crea.org.mx/" class="crea">CREA A.C.</a></li>
				<li class="col-xs-4"><a href="http://gobiernofacil.com" class="gobiernofacil" title="Gobierno Fácil">Gobierno Fácil</a></li>
			</ul>
			</div>
		</div>
	</div>
	<!-- participantes-->
	<div class="integrantes participantes">
		<div class="container">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2">
					<h3>Participantes <strong>Tú Evalúas</strong></h3>
					<ul class="row">
						<li class="col-xs-4"></li>
						<li class="col-xs-4"><a href="http://www.vas.gob.mx/swb/swb/PORTALVAS/home" class="prospera">Sedesol Prospera</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="links_bottom">
		<div class="container">
			<div class="row">
			<div class="col-sm-3">
				<p><span class="tu_evaluas">Tú Evalúas</span> ©2015</p>
			</div>
			<div class="col-sm-9">
				<ul>
					<li><?php echo anchor('/que-es','¿Qué es?');?></li>					
					<li><?php echo anchor('/resultados','Resultados');?></li>
					<li><?php echo anchor('/preguntas-frecuentes','Preguntas Frecuentes');?></li>
					<li><?php echo anchor('/terminos','Términos y Condiciones');?></li>
					<li><?php echo anchor('/privacidad','Privacidad');?></li>
					<li><?php echo anchor('/contacto','Contacto');?></li>
				</ul>
			</div>
		</div>
		</div>
	</div>
</footer>
  
  <!-- JS STUFF -->
  <script>
  var agentesFormSettings = {
        key       : "<?php echo $applicant->form_key; ?>",
        title     : "<?php echo $blueprint->title; ?>",
        id        : <?php echo $blueprint->id; ?>,
        questions : <?php echo json_encode($questions); ?>,
        options   : <?php echo json_encode($options); ?>,
        answers   : <?php echo json_encode($answers); ?>
      };

      agentesFormSettings.questions.push({
        blueprint_id   : '1',
        creation_date  : '2015-02-23 12:14:59',
        default_value  : null,
        id             : '666666',
        question       : '<p>gracias por participar en este estudio</p>',
        is_description : '1',
        order_num      : '1',
        section_id     : '666',
        type           : 'text'
      });
  </script>
  <!-- DEVELOPMENT SOURCE -->
  <script data-main="/js/main" src="/js/bower_components/requirejs/require.js"></script>
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45473222-7', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>