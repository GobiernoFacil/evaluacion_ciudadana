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
    var app = new Controller({form : form});
  </script>
</body>
</html>