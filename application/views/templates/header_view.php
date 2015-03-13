<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="es" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $title;?></title>
	<meta name="description" content="<?php echo $description;?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/img/favicon.ico">
	<link href='http://fonts.googleapis.com/css?family=Raleway:700,400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body <?php echo (!isset($body_class)) ? '' : 'class="' . $body_class . '"';?>>

<?php if (isset($body_class) && ($body_class == "home")):?>
<div class="clearfix">
		<nav class="col-sm-5 col-sm-offset-7">
			<ul>
				<li><?php echo anchor('/about','¿Qué es?');?></li>
				<li><?php echo anchor('/datos','Resultados');?></li>
			</ul>
		</nav>
</div>
<?php else:?>
<div class="container">
	<div class="row">
		<nav class="col-sm-3">
			<a href="/" class="tuevaluas">Tú evalúas</a>
		</nav>
		<nav class="col-sm-6 col-sm-offset-3">
			<ul>
				<li><?php echo anchor('/about','¿Qué es?');?></li>
				<li><?php echo anchor('/datos','Resultados');?></li>
			</ul>
		</nav>
	</div>
</div>		
<?php endif;?>
