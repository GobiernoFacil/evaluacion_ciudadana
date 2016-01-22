<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="title">Encuestas</h1>
			<div class="row">
				<!-- add survey-->
				<div class="col-sm-4">
					<section class="box">
			  		<h2>Crear encuesta</h2>

			  		<!-- SEARCH SURVEY -->
			  		<form id="search-survey" name="search-survey" method="post" class="row" action="<?= site_url("wackyland/surveys/search_survey"); ?>">
					  <div class="col-sm-12">
					  	<p><label>Buscar encuesta: </label> 
						  	<input type="text" name="query" class="typeahead">
					  	</p>
					  </div>
					</form>
					<!-- SEARCH SURVEY ENDS -->

					<!-- SEARCH USERS -->
			  		<form id="search-user" name="search-user" method="post" class="row" action="<?= site_url("wackyland/admins/search_user"); ?>">
					  <div class="col-sm-12">
					  	<p><label>Buscar usuario (email): </label> 
						  	<input type="text" name="query" class="typeahead">
					  	</p>
					  </div>
					</form>
					<!-- SEARCH USERS ENDS -->


					<form name="add-survey" method="post" class="row" action="<?= site_url("wackyland/surveys/create"); ?>">
					  <div class="col-sm-12">
					  	<p><label>Título: </label> 
						  	<input type="text" name="title">
					  	</p>
					  </div>
					  <div class="col-sm-12">
					 	 <p><input type="submit" value="crear encuesta"></p>
					  </div>
					</form>
					</section>
				</div>
				
				<!-- survey list-->
				<div class="col-sm-8">
					<section class="box">
					  <h2>Encuestas</h2>
					  <h3>Total de Encuestas
						  <strong><?php echo count($surveys);?></strong>
					  </h3>
					  
					  <ul class="list">
						  <li class="row los_titles">
						  	 <div class="col-sm-10">
							  	 <h4>Nombre</h4>
						  	 </div>
						  	 <div class="col-sm-2">
							  	  <h4>Acciones</h4>
						  	 </div>
						  </li>
					  <?php foreach($surveys as $survey): ?>
					    <li class="row">
					      <div class="col-sm-10">
					      <a href="<?= site_url("wackyland/surveys/update/" . $survey->id); ?>">
					        <?php echo $survey->title; ?>
					      </a>
					      </div>
					       <div class="col-sm-2">
						   		<a href="<?= site_url("surveys/eliminar/" . $survey->id); ?>" class="danger">Eliminar</a>
					       </div>
					    </li>
					  <?php endforeach; ?>
					  </ul>
					</section>
				</div>
				
			</div><!--- ends row-->
		</div>
	</div>
</div>

<script src="/js/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/js/bower_components/typeahead.js/dist/typeahead.jquery.min.js"></script>
<script src="/js/bower_components/typeahead.js/dist/bloodhound.min.js"></script>
<script>
	$(document).ready(function(){
	
		var surveys = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			// prefetch: $("#search-survey").attr("action"),
			
			remote: {
				url: $("#search-survey").attr("action"),
				prepare : function(a, b){
					var base = $("#search-survey").attr("action"),
					    full = base + "?query=" + a;

					b.url = full;
					return b;
				}

			}
			
		});

		$('#search-survey .typeahead').typeahead(null, {
			name: 'query',
			display: 'title',
			source: surveys
		});

		var users = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			// prefetch: $("#search-survey").attr("action"),
			
			remote: {
				url: $("#search-user").attr("action"),
				prepare : function(a, b){
					var base = $("#search-user").attr("action"),
					    full = base + "?query=" + a;

					b.url = full;
					return b;
				}

			}
			
		});

		$('#search-user .typeahead').typeahead(null, {
			name: 'query',
			display: 'email',
			source: users
		});

		$('.typeahead').bind('typeahead:select', function(ev, suggestion){
			console.log(suggestion);
			if(suggestion.email){
				window.location.href = "<?= site_url("bienvenido/usuarios"); ?>/" + suggestion.id;
			}
			else{
				window.location.href = "<?= site_url("wackyland/surveys/update"); ?>/" + suggestion.id;
			}
			// window.location.href = "<?= site_url("wackyland/surveys/update"); ?>/" + suggestion.id;
		});
	});
</script>