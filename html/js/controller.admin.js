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
      Option      = require('text!templates/option_item.admin.html'),
      Section_nav = require('text!templates/section_selector.admin.html');


  //
  // I N I T I A L I Z E   T H E   B A C K B O N E   " C O N T R O L L E R "
  // --------------------------------------------------------------------------------
  //
  var controller = Backbone.View.extend({
    
    // 
    // [   DEFINE THE EVENTS   ]
    // 
    //
    events :{
      // [ SURVEY NAVIGATION ]
      'click #survey-navigation-menu a' : 'render_section',
      // [ UPDATE TITLE ]
      'focus #survey-app-title input'   : '_enable_save',
      'blur #survey-app-title input'    : '_disable_save',
      // [ ADD QUESTION ]
      'change #survey-add-question input[name="type"]'        : '_set_is_type',
      'click #survey-add-buttons a.add-question'              : 'render_question_form',
      'click #survey-add-question-btn'                        : '_save_question',
      // [ ADD OPTION ]
      'click #survey-add-options li a'  : '_remove_option',
      'focus #survey-add-options input' : '_enable_save_option',
      'blur #survey-add-options input'  : '_disable_save_option',
      // [ ADD HTML ] 
      'click #survey-add-buttons a.add-text' : 'render_content_form',
      'click #survey-add-content-btn'        : '_save_content'
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
    answer_template  : _.template(Option),

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
      this._render_new_option = $.proxy(this._render_new_option, this);
      // [ THE LISTENERS ]
      this.listenTo(this.model, 'sync', this._render_saved_title);
      this.listenTo(this.sub_collection, 'add', this._render_question);
      this.listenTo(this.sub_collection, 'remove', this._remove_question);
      // [ FETCH SHORTCUTS ]
      this.html = {
        navigation_menu : this.$('#survey-navigation-menu'),
        question_form   : this.$('#survey-add-question'),
        content_form    : this.$('#survey-add-content'),
        answers_form    : this.$('#survey-add-options')
      };
      // [ RENDER ]
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
      // [0] guarda referencias con nombres cortos
      var sec_nav = this.$('#survey-navigation-menu'); // SEC-NAV #lol // this.html_items.navigation_menu
      // [1] agrega el título
      this.$('#survey-app-title input').val(this.model.get('title'));
      // [2] crea el navegador de secciones
      this.sections.each(function(model){
        sec_nav.append(this.sec_nav_template(model.attributes));
      }, this);
      // [3] muestra la información de la primera sección disponible
      this.render_section(1);
    },

    // [ RENDER QUESTIONS FROM A SECTION ]
    //
    //
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

    // [ RENDER SINGLE QUESTION ]
    //
    //
    _render_question : function(model, collection){
      var container = this.$('#survey-question-list'),
          question  = new Question({model : model});

      this.$el.append(question.render().el);
    },

    // [ SHOW THE LOADING STATUS: TITLE ]
    //
    //
    _render_saving_title : function(e){

    },

    // [ SHOW THE SUCCESS STATUS: TITLE ]
    //
    //
    _render_saved_title : function(model, response, options){
      console.log(model, response, options);
    },

    // [ SHOW THE ADD QUESTION FORM ]
    //
    //
    render_question_form : function(e){
      e.preventDefault();
      this.html.question_form.find('input[value="text"]')[0].checked = true;
      this.html.content_form.hide();
      this.html.question_form.show();
    },

    // [ SHOW THE ADD HTML FORM ]
    //
    //
    render_content_form : function(e){
      e.preventDefault();
      this.html.content_form.show();
      this.html.answers_form.hide();
      this.html.question_form.hide();
    },

    // [ ADD NEW ANSWER OPTION ]
    //
    //
    _render_new_option : function(e){
      if(e.keyCode === 13 && e.target.value){
        var name = _.uniqueId('lp');
        this.html.answers_form.children('ul').append(this.answer_template({
          name     : name, 
          value    : '',
          is_first : false
        }));
        this.html.answers_form.find('input[name="' + name + '"]')[0].focus();
      }
    },

    //
    // I N T E R A C T I O N
    // --------------------------------------------------------------------------------
    //

    // [ ADD A LISTENER TO THE ENTER BTN ]
    //
    //
    _enable_save : function(e){
      window.onkeyup = this._update_title;
    },

    // [ REMOVE THE LISTENER TO THE ENTER BTN ]
    //
    //
    _disable_save : function(e){
      window.onkeyup = false;
      this._update_title();
    },

    _enable_save_option : function(e){
      window.onkeyup = this._render_new_option;
    },

    _disable_save_option : function(e){
      window.onkeyup = false;
    },

    _remove_option : function(e){

    },

    _set_is_type : function(e){
      var container = this.html.answers_form.children('ul')[0];
      if(e.currentTarget.value === 'multiple'){
        if(! container.getElementsByTagName('li').length){
          this.$(container).append(this.answer_template({
            name     : _.uniqueId('lp'), 
            value    : '',
            is_first : 1
          }));
        }
        this.html.answers_form.show();
      }
      else{
        this.html.answers_form.hide();
      }
    },




    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //

    // [ UPDATE TITLE ] 
    //
    //
    _update_title : function(e){
      if(e === void 0 || e.keyCode === 13){
        var title = this.$('#survey-app-title input').val();
        if(title){
          this.model.set({title : title});
          this.model.save();
        }
      }
    },

    // [ SAVE QUESTION ] 
    //
    //
    _save_question : function(e){

    },

    // [ SAVE CONTENT ] 
    //
    //
    _save_content : function(e){

    }

  });

  return controller;
});