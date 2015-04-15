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
	<link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body class="backend">


<header class="pg">
	<div class="clearfix">
		<nav class="col-sm-3 col-sm-offset-1">
			<a href="/" class="tuevaluas">Tú evalúas</a>
		</nav>
		<nav class="col-sm-1 col-sm-offset-7">
			<ul>
				<li>
					<?php echo anchor('#','Salir');?>
				</li>
			</ul>
		</nav>
	</div>	
</header>	
<nav class="nav_back">
	<div class="container">
		<div class="row">
	    <ul>
      <li><a href="<?= site_url("wackyland/surveys"); ?>">Encuestas</a></li>
      <li><a href="<?= site_url("wackyland/admins"); ?>">usuarios</a></li>
      <li><a href="<?= site_url("wackyland/opendata"); ?>">Datos abiertos</a></li>
      <li><a href="<?= site_url("wackyland/lists"); ?>">Correos</a></li>
    </ul>
		</div>
	</div>
</nav>