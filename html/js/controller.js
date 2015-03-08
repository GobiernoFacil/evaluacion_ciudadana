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
      // dividido el cuestionario
      this.q_options = new Backbone.Collection(agentesFormSettings.options);
      this.answers   = new Backbone.Collection(agentesFormSettings.answers);
      this.sections  = [];

      // [ UPDATE THE COLLECTION ]
      // si la pregunta ya fue contestada, se le asigna un valor por default;
      // este valor viene directo del servidor, y no se guarda nuevamente, a menos
      // que la respuesta se actualice. El valor de la respuesta también sirve para 
      // saber si el usuario ha terminado de contestar cada sección.
      this._update_questions();

      // [ RENDER ]
      // genera el HTML para el formulario
      this.render();
    },

    //
    // R E N D E R   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
    render : function(){
      // [ THE SECTIONS ]
      // agrega un <fieldset> por cada sección del formulario
      // cada uno puede contener descripciones y preguntas.

      // [1] obtiene la lista de secciones de la colección de preguntas 
      var sections  = _.uniq(this.collection.pluck('section_id'));

      // [2] para cada sección, genera un view (Section), que incluye una 
      //     colección de preguntas y una referencia al controller (por si ocupa).
      _.each(sections, function(section){
        var collection = new Backbone.Collection(this.collection.where({section_id : section}));
        this.sections.push(new Section({collection : collection, controller : this}));
      }, this);

      //  [3] por ahora, después de generar la sección, la renderea, pero esto 
      //      puede/debe cambiar en el futuro.
      _.each(this.sections, function(section){
        this.$('#survey').append(section.render().el);
      }, this);
    },


    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
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