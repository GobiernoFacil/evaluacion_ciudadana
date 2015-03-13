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
<body <?php echo (!isset($body_class)) ? '' : 'class="' . $body_class . '"';?>>

<?php if (isset($body_class) && ($body_class == "home")):?>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.0";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
<div class="clearfix">
	<nav class="col-sm-3">
		<div class="fb">
			<?php $url= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];?>
			<div class="fb-share-button" data-href="<?php  echo $url;?>" data-layout="button_count"></div>
		</div>
		<div class="tw">
			<a class="twitter-share-button" href="https://twitter.com/share">Tweet</a>
			<script>
window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));
			</script>
		</div>
	</nav>
		<nav class="col-sm-6 col-sm-offset-3">
			<ul>
				<li><?php echo anchor('/about','¿Qué es?', array("class"=>"hm_link"));?></li>
				<li><?php echo anchor('/datos','Resultados',array("class"=>"hm_link"));?></li>
			</ul>
		</nav>
</div>
<?php else:?>
<header class="pg">
	<div class="clearfix">
		<nav class="col-sm-3 col-sm-offset-1">
			<a href="/" class="tuevaluas">Tú evalúas</a>
		</nav>
		<nav class="col-sm-5 col-sm-offset-3">
			<ul>
				<li><?php echo anchor('/about','¿Qué es?');?></li>
				<li><?php echo anchor('/datos','Resultados');?></li>
				<li><?php echo anchor('/preguntas','Preguntas Frecuentes');?></li>
			</ul>
		</nav>
	</div>	
</header>	
<?php endif;?>
