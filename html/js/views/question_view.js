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
      Location    = require('text!templates/survey_location.html'),
      Loc_option  = require('text!templates/survey_location_option.html');

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
      'click input[type="radio"]'   : 'save_response',
      'blur input[type="text"]'     : 'save_response',
      'change input[type="hidden"]' : 'save_response',

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
    lo_temp  : _.template(Loc_option),

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
        this._render_input();
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
      if(this.model.get('default_value')){
        var location = this.model.get('default_value');
        var state    = location.slice(0,2);
        var city     = location.slice(2,5);
        var locality = location.slice(5);
        
        this.$('select.estado').val(state);
        this._set_cities(state, city);
        this._set_localities(state, city, locality);
      }
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
      // [ THE STATE ] 
      // [1] obtiene el valor del select
      var state = this.$(e.currentTarget).val();
      // [2] limpia la lista de municipios y localidades
      this.$('select.municipio option.data').remove();
      this.$('select.localidad option.data').remove();
      // [3] genera la nueva clave de ubicación
      var location = state + String('0000000');
      // [4] salva la nueva ubicación
      this.$('input[type="hidden"]').val(location).change();
      // [5] si es algún estado en particular, carga los municipios
      if(Number(state)) this._set_cities(state);
    },

    _update_city : function(e){
      // [ THE CITY ]
      // [1] obtiene el valor del estado y el municipio
      var state = this.$('select.estado').val();
      var city  = this.$('select.municipio').val();
      // [2] limpia la lista de localidades
      this.$('select.localidad option.data').remove();
      // [3] genera la nueva clave de ubicación
      var location = state + String(city) + String('0000');
      // [4] salva la nueva ubicación
      this.$('input[type="hidden"]').val(location).change();
      // [5] si es algún estado en particular, carga los municipios
      if(Number(city)) this._set_localities(state, city);
    },

    _update_locality : function(e){
      // [ THE LOCALITY ]
      // [1] obtiene el valor de la localidad, el municipio y el estado
      var state    = this.$('select.estado').val();
      var city     = this.$('select.municipio').val();
      var locality = this.$(e.currentTarget).val();
    
      // [3] genera la nueva clave de ubicación
      var location = state + String(city) + String(locality);
      // [4] salva la nueva ubicación
      this.$('input[type="hidden"]').val(location).change();
    },

    _set_cities : function(state, city){
      var that = this;
      $.get('/index.php/municipios/' + state, {}, function(data){
        if(data.length){
          _.each(data, function(city){
            this.$('select.municipio').append(this.lo_temp(city));
          }, that);

          if(city || false){
            that.$('select.municipio').val(city);
          }
        }
      }, 'json');
    },

    _set_localities : function(state, city, locality){
      var that = this;
      $.get('/index.php/localidades/' + state + '/' + city, {}, function(data){
        if(data.length){
          _.each(data, function(loc){
            this.$('select.localidad').append(this.lo_temp(loc));
          }, that);

          if(locality || false){
            that.$('select.localidad').val(locality);
          }
        }
      }, 'json');
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
      // [2.1] actualiza el valor antes de enviarlo al servidor; esto
      //       para evitar que el usuario tenga una navegación equivocada
      //       en caso de que el servidor tarde en responder y actualizar
      //       el valor de la pregunta
        this.model.set({'default_value' : res});

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