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
        id    : form_id,
        key   : form_key, 
        title : form_title
      });

      // [ THE COLLECTION ]
      // se inicia la colección con todas las preguntas del
      // formulario
      this.collection = new Backbone.Collection(form_questions);

      // [ THE OTHER COLLECTIONS ]
      // aquí se incluye la lista de opciones (y tal vez respuestas)
      // de todas las preguntas. También las secciones en las que está
      // dividido el cuestionario
      this.q_options = new Backbone.Collection(form_options);
      this.sections  = [];

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
        this.sections.push(new Section({collection : collection, parent : this}));
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
    _do_nothing : function(e){
      e.preventDefault();
    }

  });

  return controller;
});