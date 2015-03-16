<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
	<title>Contacto en Sitio Tú Evaluas</title>
</head>

<body>
<!-- saludo -->
<h2 style="color:#626C8C; font: 500 24px sans-serif; margin:20px auto; padding: 0px 20px; background: #fff;  text-align: center;">
¡Hola, alguien envío mensaje en el sitio!</h2>
<ul style="color:#6d6d6d; background: #fff;font: 500 14px sans-serif; background: #F0F2F2; margin:0 20px; padding: 10px 20px 20px;  text-align: center;">
		<li style="list-style:none; margin: 0 10px 10px 35px"><span style="color:#6d6d6d; width:150px;">Nombre:</span> <?php echo $data['name']; ?></li>
		<li style="list-style:none; margin: 0 10px 10px 35px"><span style="color:#6d6d6d; width:150px;">Email:</span> <?php echo $data['email']; ?></li>

		<li style="list-style:none; margin: 0 10px 10px 35px"><span style="color:#6d6d6d; width:150px;">Mensaje:</span> <?php echo $data['message']; ?></li>
</ul>

</body>
</html>