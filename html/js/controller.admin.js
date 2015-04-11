// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP CREATOR
// @package  : agentes
// @location : /js
// @file     : controller.admin.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){
  
  //
  // L O A D   T H E   A S S E T S   A N D   L I B R A R I E S
  // --------------------------------------------------------------------------------
  //
  var Backbone     = require('backbone'),
      Section_nav  = require('text!templates/section_selector.admin.html');


  //
  // I N I T I A L I Z E   T H E   B A C K B O N E   " C O N T R O L L E R "
  // --------------------------------------------------------------------------------
  //
  var controller = Backbone.View.extend({
    
    // 
    // [ DEFINE THE EVENTS ]
    // 
    //
    events :{
    },

    // 
    // [ SET THE CONTAINER ]
    //
    //
    el : 'body',

    // 
    // [ THE TEMPLATES ]
    //
    //
    sec_nav_template : _.template(Section_nav),

    //
    // [ THE INITIALIZE FUNCTION ]
    //
    //
    initialize : function(){

      // [ THE MODEL ]
      this.model = new Backbone.Model(SurveySettings.blueprint);
      // [ THE COLLECTION ]
     this.collection = new Backbone.Collection(SurveySettings.questions);

      // [ THE OTHER COLLECTIONS ]
      this.q_options = new Backbone.Collection(SurveySettings.options);
      this.sections  = new Backbone.Collection(SurveySettings.sections);

      // [ RENDER ]
      this.render();
    },

    //
    // R E N D E R   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
    render : function(){
      // [0] guarda referencias con nombres cortos
      var sec_nav = this.$('#survey-navigation-menu'); // SEC-NAV #lol
      // [1] agrega el título
      this.$('#survey-app-title input').val(this.model.get('title'));
      // [2] agrega las secciones
      this.sections.each(function(model){
        sec_nav.append(this.sec_nav_template(model.attributes));
      }, this);
      // [3] muestra la información de la primera sección disponible
    }

    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //

  });

  return controller;
});