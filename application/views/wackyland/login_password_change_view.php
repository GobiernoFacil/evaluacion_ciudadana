<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Cambia tu password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/img/favicon.ico">
	<link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/styles.css">
  </head>
  <body class="login">
	  <div class="container">
		  <div class="row">
		  	<div class="col-sm-8 col-sm-offset-2">
				<h1 class="tu_evaluas">Tú Evalúas</h1>
			</div>
		  	<div class="col-sm-6 col-sm-offset-3">
          <?php if($success): ?>
          <p>Tu contraseña se ha actualizado</p>
          <?php else: ?>
		  		<form name="nock-nock" method="post">
            <!-- [ ERROR MESSAGE ] -->
            <?php if($error): ?>
            <p>No se logró cambiar tu contraseña. Esta debe contener por lo menos ocho caracteres</p>
            <?php endif; ?>
    			  
            <!-- [ THE PASSWORD ] -->
            <p><label>nueva contraseña</label>
	    			  <input type="password" name="pass" id="the-pass"></p>
    			  <p><input type="submit" value="actualizar contraseña"></p>
    			</form>
        <?php endif; ?>
		  	</div>
		  </div>
	  </div>
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45473222-7', 'auto');
  ga('send', 'pageview');

</script>
<script>
  // [ CRAPY VALIDATION ]
  var form = document.getElementsByTagName('form')[0];

  form.onsubmit = function(e){
    e.preventDefault();
    var pass  = document.getElementById('the-pass');

    pass.className = '';

    if(! pass.value){
      pass.className += ' ' + 'error'; 
      return;
    } 
   
    this.submit();
  }
</script>
  </body>
</html>