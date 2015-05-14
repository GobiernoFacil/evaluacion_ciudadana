// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP
// @package  : agentes
// @location : /js/views
// @file     : section_view.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){

  //
  // L O A D   T H E   A S S E T S   A N D   L I B R A R I E S
  // --------------------------------------------------------------------------------
  //
  var Backbone = require('backbone'),
  //  Question: Se encarga de generar el HTML de cada pregunta y su conexión con el servidor.
      Question = require('views/question_view'),
  //  Next_btn: Es un template para el botón de next
      Next_btn = require('text!templates/next_button.html');

  //
  // I N I T I A L I Z E   T H E   B A C K B O N E   V I E W
  // --------------------------------------------------------------------------------
  //
  var section = Backbone.View.extend({

    // 
    // [   DEFINE THE EVENTS   ]
    // 
    //
    events : {
      'click .next' : 'next'
    },

    // 
    // [ SET THE CONTAINER ]
    //
    //
    tagName : 'fieldset',

    // 
    // [ THE TEMPLATES ]
    //
    //
    template : _.template(Next_btn),

    //
    // [ THE INITIALIZE FUNCTION ]
    //
    //
    initialize : function(settings){
      this.controller = settings.controller;
      this.questions = [];
      this.pos = settings.pos;
      this.render();
    },

    //
    // R E N D E R   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //

    // [ THE RENDER ]
    //
    //
    render : function(){
      // [ THE SECTION ]
      // una sección es un <fieldset> que contiene descripciones, algunas preguntas 
      // y un botón para avanzar en el cuestionario

      // [ THE QUESTIONS ]
      this.collection.each(function(question){
      // [1] genera una colección de opciones para cada pregunta. La lista de opciones
      //     está disponible en el controller.
        var opt = new Backbone.Collection(this.controller.q_options.where({question_id : question.id}));
      // [2] se genera un View con la pregunta, y se le pasan las siguiente variables:
      //     * el modelo de la pregunta (question)
      //     * la colección de opciones (opt)
      //     * la referencia al controller (this.controller)
        var q = new Question({model : question, opt : opt, controller : this.controller});
      // [3] una vez generado el View, inmediatamente se "renderea" y el HTML es
      //     agregado a la sección.
        this.$el.append(q.render().el);
      // [4] el view se guarda en un array de la sección, por si las flys
        this.questions.push(q);
      }, this);

      // [5] se agrega el botón de "siguiente"
      this.$el.append(Next_btn);

      return this;
    },

    //
    // N A V   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //

    // [ THE NEXT BTN ]
    //
    //
    next : function(e){
      e.preventDefault();
      // la función de render_next() está definida en controller.js, que es el
      // archivo que carga este script
      this.controller.render_next();

      // se mueve al inicio el scroll, por si está muy abajo el asunto
      $('html, body').animate({scrollTop : 0},800);
    }
  });

  return section;
});