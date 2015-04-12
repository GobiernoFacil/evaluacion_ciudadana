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
      Question    = require('views/question_view.admin'),
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
      'click #survey-navigation-menu a' : 'render_section',
      'focus #survey-app-title input'   : '_enable_save',
      'blur #survey-app-title input'    : '_disable_save'
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
      this.model         = new Backbone.Model(SurveySettings.blueprint);
      this.model.url     = "/index.php/surveys/title/update";
      // [ THE COLLECTION ]
     this.collection     = new Backbone.Collection(SurveySettings.questions);
     this.sub_collection = new Backbone.Collection([]);
      // [ THE OTHER COLLECTIONS ]
      this.q_options     = new Backbone.Collection(SurveySettings.options);
      this.sections      = new Backbone.Collection(SurveySettings.sections);
      this.rules         = new Backbone.Collection(SurveySettings.rules);
      // [ FIX THE SCOPES ]
      this._update_title = $.proxy(this._update_title, this);
      // [ THE LISTENERS ]
      this.listenTo(this.model, 'sync', this._render_saved_title);
      this.listenTo(this.sub_collection, 'add', this._render_question);
      this.listenTo(this.sub_collection, 'remove', this._remove_question);
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
      this.render_section(1);
    },

    render_section : function(e){
      if(typeof e !== "number") e.preventDefault();
      // [1] obitiene la nueva sección
      var section = typeof e === "number" ? String(e) : e.currentTarget.getAttribute('data-section');
      // [2] le asigna la clase de seleccionado
      var sec_nav = this.$('#survey-navigation-menu');
      sec_nav.children().removeClass('current');
      sec_nav.children().eq(Number(section)-1).addClass('current');
      // [3] obtiene las reglas
      //
      // [4] crea la lista de preguntas
      this.sub_collection.set(this.collection.where({section_id : section}));
    },

    _render_question : function(model, collection){
      var container = this.$('#survey-question-list'),
          question  = new Question({model : model});

      this.$el.append(question.render().el);
    },

    _render_saving_title : function(e){

    },

    _render_saved_title : function(model, response, options){
      console.log(model, response, options);
    },

    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
    _enable_save : function(e){
      window.onkeyup = this._update_title;
    },

    _disable_save : function(e){
      window.onkeyup = false;
      this._update_title();
    },

    _update_title : function(e){
      if(e === void 0 || e.keyCode === 13){
        var title = this.$('#survey-app-title input').val();
        if(title){
          this.model.set({title : title});
          this.model.save();
        }
      }
    }

  });

  return controller;
});