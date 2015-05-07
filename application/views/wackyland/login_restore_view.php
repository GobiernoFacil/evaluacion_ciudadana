<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Se me olvidó el password!</title>
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
          <p>Se ha enviado un correo con el link para recuperar tu contraseña :D</p>
        <?php else: ?>
          <form name="nock-nock" method="post">
            <!-- [ ERROR MESSAGE ] -->
            <?php if($error): ?>
            <p>El correo no es válido!</p>
            <?php endif; ?>

            <!-- [ THE EMAIL ] -->
            <p><label>Email</label>
              <input type="text" name="email" id="the-email"></p>
            <p><input type="submit" value="recuperar contraseña"></p>
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
    var email = document.getElementById('the-email');

    email.className = '';

    if(! email.value){
      email.className += ' ' + 'error'; 
      return;
    }
   
    this.submit();
  }
</script>
  </body>
</html>