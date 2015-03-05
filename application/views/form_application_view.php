<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Test form</title>
</head>
<body>
  <h1>Primer formulario</h1>
  <div id="main">
    <form id="survey">
    
    </form>
  </div>
  
  <!-- JS STUFF -->
  <script>
  var form_key       = "<?php echo $applicant->form_key; ?>",
      form_title     = "<?php echo $blueprint->title; ?>",
      form_id        = <?php echo $blueprint->id; ?>,
      form_questions = <?php echo json_encode($questions); ?>,
      form_options   = <?php echo json_encode($options); ?>;
  </script>
  <!-- DEVELOPMENT SOURCE -->
  <script data-main="/js/main" src="/js/bower_components/requirejs/require.js"></script>
</body>
</html>