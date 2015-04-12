// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP CREATOR
// @package  : agentes
// @location : /js/views
// @file     : question_view.admin.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){

  //
  // L O A D   T H E   A S S E T S   A N D   L I B R A R I E S
  // --------------------------------------------------------------------------------
  //
  var Backbone  = require('backbone'),
      Container = require('text!templates/question_item.admin.html');

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
    

      // [ LOCATION QUESTIONS]
    },

    // -----------------
    // SET THE CONTAINER
    // -----------------
    //
    tagName : 'li',

    // -----------------
    // SET THE TEMPLATES
    // -----------------
    //
    template : _.template(Container),

    // ------------------------
    // THE INITIALIZE FUNCTION
    // ------------------------
    //
    initialize : function(){
      this.listenTo(this.model, 'remove', this.remove);
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
      this.$el.append(this.template(this.model.attributes));
      return this;
    }
      /*
      // [ THE QUESTION TYPES ]

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
    */

    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
   

  });

  return section;
});