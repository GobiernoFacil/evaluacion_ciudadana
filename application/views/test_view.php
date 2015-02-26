<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Test form</title>
</head>
<body>
  <h1>Primer formulario</h1>
  <div id="main">
  </div>
  
  <!-- JS STUFF -->
  <script src="/js/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="/js/bower_components/underscore/underscore-min.js"></script>
  <script src="/js/bower_components/backbone/backbone.js"></script>
  <script src="/js/demo.js"></script>
  <script>
    var form      = <?php echo json_encode($form); ?>;
    var questions = <?php echo json_encode($questions); ?>;
    var app       = new Controller({
      form      : form, 
      questions : questions
    });

    app.render();
  </script>
</body>
</html>