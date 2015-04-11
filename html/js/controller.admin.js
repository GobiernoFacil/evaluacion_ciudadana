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
  var Backbone    = require('backbone'),
      Qestion     = require('views/question_view'),
      Section_nav = require('text!templates/section_selector.admin.html');


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
      'click #survey-navigation-menu a' : 'select_section'
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
    sec_nav_template : _.template(Section_nav)

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
      this.rules     = new Backbone.Collection(SurveySettings.rules);

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
      // [2] crea el navegador de secciones
      this.sections.each(function(model){
        sec_nav.append(this.sec_nav_template(model.attributes));
      }, this);
      // [3] muestra la información de la primera sección disponible
    },

    select_section : function(e){
      if(typeof e !== "number") e.preventDefault();
      // [1] obitiene la nueva sección
      var section = typeof e === "number" ? e : e.currentTarget.getAttribute('data-section');
      // [2] obtiene las reglas
      //
      // [3] crea la lista de preguntas
      this._render_questions(section);
    },

    _render_questions : function(section){
      var questions = this.collection.where({section_id : section});
      var container = this.$('#survey-question-list');

      _.each(questions, function(question){
        // 
      }, this);
    }

    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //

  });

  return controller;
});