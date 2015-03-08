// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP
// @package  : agentes
// @location : /js/views
// @file     : question_view.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){

  //
  // L O A D   T H E   A S S E T S   A N D   L I B R A R I E S
  // --------------------------------------------------------------------------------
  //
  var Backbone = require('backbone'),
      Description = require('text!templates/survey_description.html'),
      Question    = require('text!templates/survey_question.html'),
      Option      = require('text!templates/survey_option.html'),
      Input       = require('text!templates/survey_input.html');

  //
  // I N I T I A L I Z E   T H E   B A C K B O N E   V I E W
  // --------------------------------------------------------------------------------
  //
  var section = Backbone.View.extend({

    // ------------------
    // DEFINE THE EVENTS
    // ------------------
    //
    events : {
      'click input[type="radio"]' : 'save_response',
      'blur input[type="text"]'   : 'save_response'
    },

    // -----------------
    // SET THE CONTAINER
    // -----------------
    //
    tagName : 'div',

    // -----------------
    // SET THE TEMPLATES
    // -----------------
    //
    template : _.template(Question),
    des_temp : _.template(Description),
    opt_temp : _.template(Option),
    inp_temp : _.template(Input),

    // ------------------------
    // THE INITIALIZE FUNCTION
    // ------------------------
    //
    initialize : function(settings){
      // [ THE INITAL SETTINGS ]
      // [1] this.opt es una colección de opciones (si es de opción múltiple)
      // [2] this.controller es una referencia al objeto app.
      // [3] this.server_value es el valor de la respuesta en el servidor
      this.opt          = settings.opt;
      this.controller   = settings.controller;
      this.server_value = false;

      // [ THE HTML ]
      // las respuestas generan su HTLM desde el momento que se crean
      this.render();
    },

    //
    // R E N D E R   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
    render : function(){
      // [ THE QUESTION ]
      // escribe dentro de un <p> la pregunta y agrega un <ul>
      // para agregar una lista de posibles respuestas. Si es 
      // una descripción, agrega un <div> en lugar del <p>
      // y no incluye un <ul>
      if(Number(this.model.get('is_description'))){
        this.$el.html(this.des_temp(this.model.attributes));
      }
      else{
        this.$el.html(this.template(this.model.attributes));
      }

      // [ THE OPTIONS ]
      // [ A ] Si hay opciones disponibles, las pone como una lista
      //       de <radio>
      if(this.opt.length){
        this.opt.each(function(option){
          this.$el.append(this.opt_temp(option.attributes));
        }, this);
      }

      // [ B ] Si no hay opciones, pero no es descripción, agrega
      //       un <input> para texto. Un elemento de "pregunta" en la DB
      //       también puede ser HTML con algún texto que describa parte
      //       del proceso
      else if(! Number(this.model.get('is_description'))){
        this.$el.append(this.inp_temp(this.model.attributes));
      }

      return this;
    },

    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
    save_response : function(e){
      // [ UPDATE THE SERVER ]
      // Cada que se contesta o cambia una respuesta, esta es enviada al
      // servidor.
      // [1] obtiene y revisa que la respuesta sea válida
      var res = this.$(e.currentTarget).val();
      if(res){
      // [2] genera el objeto de respuesta al servidor
        var server_res = {
          question_value : res,
          form_key       : this.controller.model.get('key'),
          question_id    : this.model.id
        };

      // [3] envia la respuesta al servidor
        var that = this;
        $.post('/index.php/respuestas', server_res, function(data){
      // [4] Si la operación tuvo "éxito-hacker cívico", se guarda la respuesta
      //     dentro del view actual. (Por si las flys)
          if(data){
            that.server_value = data;
          }
        }, 'json');
      }
    }
  });

  return section;
});