<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Test form</title>
</head>
<body>
  
  <!-- JS STUFF -->
  <script src="/js/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="/js/bower_components/underscore/underscore-min.js"></script>
  <script src="/js/bower_components/backbone/backbone.js"></script>
  <script src="/js/demo.js"></script>
  <script>
    var form = <?php echo json_encode($form); ?>;
    var questions = <?php echo json_encode($questions); ?>;
    /* <?php echo count($questions); ?> */
    var app = new Controller({
      form      : form, 
      questions : questions
    });
  </script>
</body>
</html>