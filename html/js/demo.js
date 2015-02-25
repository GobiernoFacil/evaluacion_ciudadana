/* 
* ENDPOINTS
* ----------------------------------------
*/
var FORM_Endpoint     = '/forms',
    SECTION_Endpoint  = '/sections',
    QUESTION_Endpoint = '/questions',
    OPTIONS_Endpoint  = '/question_options';


/* 
* MODELS
* ----------------------------------------
*/

// FORM model
// ---------------------------------------
//
var Form_model = Backbone.Model.extend({
  // endpoint
  urlRoot : FORM_Endpoint,
  // configure the form
  initialize : function(settings){
    this.sections = new Backbone.Collection(settings.sections, {
      model : Section_model,
      url   : SECTION_Endpoint
    });
  },
  // initial data
  defaults : function(){
    return {
      form_id    : false,
      position   : 0,
      initialize : new Date(),
      name       : false,
      enabled    : true
    }
  }
});

// QUESTION model
// ---------------------------------------
//
var Question_model = Backbone.Model.extend({
  initialize : function(){
  },

  defaults : function(){
    return {
      description : false,
      options     : [],
      values      : [],
      type        : false,
      value       : null,
      html        : false
    }
  }
});

// OPTION model
// ---------------------------------------
//
var Option_model = Backbone.Model.extend({
  initialize : function(){

  },

  defaults : function(){
    return {
      question_id  : null,
      blueprint_id : null,
      description  : '',
      value        : null,
      name         : null,
      order_num    : null
    }
  }
});


// SECTION model
// ---------------------------------------
//
var Section_model = Backbone.Model.extend({
  initialize : function(settings){
    // html content or question
    this.questions = new Backbone.Collection(settings.questions, {
      model : Question_model,
      url   : QUESTION_Endpoint
    });
  },
  defaults : function(){
    return {
      rules : false
    }
  }
});


/* 
* VIEWS
* ----------------------------------------
*/

// Controller
// ---------------------------------------
//
var Controller = Backbone.View.extend({
  
  el : 'body',

  initialize : function(data){
    this.model      = new Form_model(data.form);
    this.collection = new Backbone.Collection(data.questions, {
      model : Question_model
    });
  }
});

// Question
// ---------------------------------------
//
var question = Backbone.View.extend({
  events : {
    'click a.add-option' : add_option
  },

  tagName : 'div',

  initialize : function(){
    this.render();

  },

  render : function(){

  },

  add_option : function(e){

  }
});

/* 
* TEMPLATES
* ----------------------------------------
*/

var question_template = '<p><%= question %></p><ul></ul><p><a class="add-option" href="#">agregar respuesta</p>';

