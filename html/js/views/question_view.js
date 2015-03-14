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
      Input       = require('text!templates/survey_input.html'),
      Location    = require('text!templates/survey_location.html');

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
      // [ GENERAL QUESTIONS]
      'click input[type="radio"]' : 'save_response',
      'blur input[type="text"]'   : 'save_response',

      // [ LOCATION QUESTIONS]
      'change select[class="estado"]'    : '_update_state',
      'change select[class="municipio"]' : '_update_city',
      'change select[class="localidad"]' : '_update_locality',
    },

    // -----------------
    // SET THE CONTAINER
    // -----------------
    //
    tagName : 'div',
    className : 'col-sm-10 col-sm-offset-1 count',

    // -----------------
    // SET THE TEMPLATES
    // -----------------
    //
    template : _.template(Question),
    des_temp : _.template(Description),
    opt_temp : _.template(Option),
    inp_temp : _.template(Input),
    loc_temp : _.template(Location),

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

    // --------------------
    // CALL THE MAIN RENDER
    // --------------------
    //
    render : function(){
      // [ THE QUESTION TYPES ]

      // [ THE DESCRIPTION ] 
      if(Number(this.model.get('is_description'))){
        this._render_description();
      }

      // [ THE LOCATION ]
      else if(Number(this.model.get('is_location'))){
        this._render_location();
      }

      // [ THE MULTIPLE OPTION ] 
      else if(this.opt.length){
        this._render_radio();
      }

      // [ THE OPEN QUESTION ]
      else{
        this.$el.html(this.template(this.model.attributes));
      }

      return this;
    },

    _render_description : function(){
      this.$el.html(this.des_temp(this.model.attributes));
    },

    _render_radio : function(model){
      this.$el.html(this.template(this.model.attributes));
      // [ THE OPTIONS ]
      this.opt.each(function(option){
        // [ A.1 ] le pasa a cada opción el valor del servidor para
        //         la pregunta a la que pertenece. Si ya fue respondida,
        //         le agrega el atributo "checked"; si no, no pasa nada. 
        option.set({default_value : this.model.get('default_value')});
        this.$el.append(this.opt_temp(option.attributes));
      }, this);
    },

    _render_location : function(){
      this.$el.html(this.loc_temp(this.model.attributes));
    },

    _render_input : function(){
      this.$el.html(this.template(this.model.attributes));
      this.$el.append(this.inp_temp(this.model.attributes));
    },

    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
    _update_state : function(e){

    },

    _update_city : function(e){
    },

    _update_locality : function(e){

    }

    _save_location : function(){

    },

    _get_cities : function(){

    },

    _get_localities : function(){

    },

    save_response : function(e){
      // [ UPDATE THE SERVER ]
      // Cada que se contesta o cambia una respuesta, ésta es enviada al
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
            that.model.set({'default_value' : data});
          }
        }, 'json');
      }
    }
  });

  return section;
});