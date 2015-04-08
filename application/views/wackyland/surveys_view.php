<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Pase usted</title>
  </head>
  <body>
    <h1>Encuestas</h1>

    <form name="add-survey" method="post" action="<?= site_url("wackyland/surveys/create"); ?>">
      <h2>Crear encuesta</h2>
      <p><label>Título</label><input type="text" name="title"></p>
      <p><input type="submit" value="crear encuesta"></p>
    </form>

    <section>
      <h2>Encuestas</h2>
      <ul>
      <?php foreach($surveys as $survey): ?>
        <li>
          <a href="<?= site_url("wackyland/surveys/update/" . $survey->id); ?>">
            <?php echo $survey->title; ?>
          </a>

          <a href="<?= site_url("wackyland/surveys/delete/" . $survey->id); ?>">Eliminar</a>
        </li>
      <?php endforeach; ?>
      </ul>
    </section>
  </body>
</html>