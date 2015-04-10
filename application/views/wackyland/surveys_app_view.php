<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Pase usted</title>
  </head>
  <body>
    <script>
      var SurveySettings = {
        blueprint : <?= json_encode($blueprint); ?>,
        questions : <?= json_encode($questions); ?>,
        options   : <?= json_encode($options); ?>
      };
    </script>
    <!-- DEVELOPMENT SOURCE -->
    <script data-main="/js/main.admin" src="/js/bower_components/requirejs/require.js"></script>
  </body>
</html>