// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP
// @package  : agentes
// @location : /js
// @file     : controller.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){
  
  //
  // L O A D   T H E   A S S E T S   A N D   L I B R A R I E S
  // --------------------------------------------------------------------------------
  //
  var Backbone = require('backbone'),
      Section  = require('views/section_view');

  //
  // I N I T I A L I Z E   T H E   B A C K B O N E   " C O N T R O L L E R "
  // --------------------------------------------------------------------------------
  //
  var controller = Backbone.View.extend({
    
    // ------------------
    // DEFINE THE EVENTS
    // ------------------
    //
    events :{
      'submit #survey' : '_do_nothing'
    },

    // -----------------
    // SET THE CONTAINER
    // -----------------
    //
    el : 'body',

    // ------------------------
    // THE INITIALIZE FUNCTION
    // ------------------------
    //
    initialize : function(){

      // [ THE MODEL ]
      // inicia el modelo con el id del bluprint, el identificador
      // del formulario y el título
      this.model = new Backbone.Model({
        id    : agentesFormSettings.id,
        key   : agentesFormSettings.key, 
        title : agentesFormSettings.title
      });

      // [ THE COLLECTION ]
      // se inicia la colección con todas las preguntas del
      // formulario
      this.collection = new Backbone.Collection(agentesFormSettings.questions);

      // [ THE OTHER COLLECTIONS ]
      // aquí se incluye la lista de opciones (y tal vez respuestas)
      // de todas las preguntas. También las secciones en las que está
      // dividido el cuestionario y las reglas que se aplican para navegar
      // en él.
      this.q_options = new Backbone.Collection(agentesFormSettings.options);
      this.answers   = new Backbone.Collection(agentesFormSettings.answers);
      this.sections  = [];
      this._define_nav_rules();

      // [ UPDATE THE COLLECTION ]
      // si la pregunta ya fue contestada, se le asigna un valor por default;
      // este valor viene directo del servidor, y no se guarda nuevamente, a menos
      // que la respuesta se actualice. El valor de la respuesta también sirve para 
      // saber si el usuario ha terminado de contestar cada sección.
      this._update_questions();

      // [ GENERATE SECTIONS ]
      // cada sección es un paso en la navegación del formulario.
      this._create_sections();

      // [ THE POINTER ]
      // lleva registro de dónde va el la navegación del formulario
      this.navigation_pointer = 0;

      // [ RENDER ]
      // genera el HTML para el formulario
      this.render();
    },

    //
    // R E N D E R   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
    render : function(){
      // [ THE FIRST SECTION ]
      // [1] dibuja solo la primera sección del formulario
      this.$('#survey').append(this.sections[0].el);
      // [2] por si las flys, actualiza el pointer
      this.navigation_pointer = 0;
    },

    render_next : function(){
      // [ THE n SECTION ]
      // dibuja la siguiente sección, siempre y cuando exista!

      // [1] obtiene la siguiente posición del formulario
      var position = this.navigation_pointer + 1;

      // [2] revisa que la nueva posición sea válida!
      if(position < this.sections.length){
      // [3] obtiene los requisitos para la siguiente sección
        var rules = this.nav_rules[position];
      // [4] si hay requisitos, revisa que se cumplan, y si no,
      //     que cargue los requisitos de la siguiente sección
      //     y así hasta llegar a una sección que cumpla los requisitos
      //     o al final del formulario
        while(rules){
        // [4.1] busca la pregunta que define si se despliega o no la siguiente
        //       sección. Por ahora solo se puede poner una pregunta x regla.
        //       Seguro en el futuro serán más lol.
          var question = this.collection.findWhere({id : rules.question});
        // [4.2] obtiene el valor de default de la pregunta.
          var value    = question.get('default_value');
        // [4.3] si el valor coincide con la condición, termina el ciclo
          if(rules.val.indexOf(value) > -1){
            break;
          }
        // [4.4] si el valor no coincide, pero no hay más preguntas,
        //       también termina el ciclo y renderea la última sección 
          else if(position + 1 >= this.sections.length){
            break;
          }
        // [4.5] si el valor no coincide, pero hay más secciones, mueve
        //       el pointer y revisa de nuevo
          else{
            position++;
            this.navigation_pointer++;
            rules = this.nav_rules[position];
          }
        }

      // [5] hace hueco para el siguiente contenido.
      //     aquí es donde podría haber "magia" en la interacción
        this.$('#survey').html('');
      // [6] renderea la siguiente sección
        this.$('#survey').append(this.sections[position].el);

      // [6.1] HACK: quita el botón de siguiente al llegar al final de formulario
        if(this.sections[position].pos +1 == this.sections.length) this.$('a.next').remove();

      // [7] actualiza el pointer
        this.navigation_pointer++;

      }
    },

    render_any : function(position){
      // [ THE n SECTION ]
      // dibuja CUALQUIER SECCIÓN O____O!!!!

      // [1] hace hueco para el contenido
        this.$('#survey').html('');
      // [2] renderea la siguiente sección
        this.$('#survey').append(this.sections[position].el);
      // [5] actualiza el pointer
        this.navigation_pointer = position;
    },

    render_all : function(){
      // [ THE SECTIONS ]
      // agrega un <fieldset> por cada sección del formulario;
      // cada uno puede contener descripciones y preguntas.
      _.each(this.sections, function(section){
        this.$('#survey').append(section.el);
      }, this);
    },


    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //

    _define_nav_rules : function(){
      // [ THE NAV RULES]
      // con esta guía, no se muestran todos los páneles, solo
      // los que concuerden con la navegación.
      // hace falta definir algo similar para validar las respuestas.
      this.nav_rules = [
      null, 
      null,
      {question : '6', val : ['1', '2']},
      null,
      {question : '12', val : ['1']},
      {question : '6',  val : ['1', '2']},
      {question : '6',  val : ['2']},
      {question : '6',  val : ['3']},
      {question : '25', val : ['1']},
      null,
      {question : '6',  val : ['1', '2']},
      {question : '32', val : ['1']},
      null,
      {question : '38', val : ['1']},
      null
      ];
    },

    _create_sections : function(){
      // [ THE SECTIONS ]
      // A partir de la colección de preguntas, genera una lista de secciones.
      // Con esta lista llena un Array (this.sections) de Views, uno por cada sección.

      // [1] obtiene la lista de secciones de la colección de preguntas 
      var sections  = _.uniq(this.collection.pluck('section_id'));

      // [2] para cada sección, genera un view (Section), que incluye una 
      //     colección de preguntas y una referencia al controller (por si ocupa).
      _.each(sections, function(section, pos){
        var collection = new Backbone.Collection(this.collection.where({section_id : section}));
        this.sections.push(new Section({collection : collection, controller : this, pos : pos}));
      }, this);
    },

    _update_questions : function(){
      this.collection.each(function(question){
        // [ THE ANSWER ]
        // para cada pregunta, revisa si ya ha sido contestada. Si no ha
        // sido contestada, se le asigna un valor de NULL a la propiedad
        // "default_value". Si ya ha sido respondida, se le asigna el valor
        // actual del servidor.

        // [1] busca la respuesta en la colección "answers" de este controller
        var answer = this.answers.findWhere({question_id : question.id});

        // [2] si la respuesta existe, se asigna el valor text/num a "default_value"
        if(typeof answer !== "undefined"){
          question.set({default_value : answer.get('num_value') || answer.get('text_value')});
        }

        // [3] si no se ha respondido, se asigna el valor NULL a "default_value"
        else{
          question.set({default_value : null});
        }

      }, this);
    },

    _do_nothing : function(e){
      e.preventDefault();
    }

  });

  return controller;
});