/* 
* ENDPOINTS
* ----------------------------------------
*/
var FORM_Endpoint     = '/forms',
    SECTION_Endpoint  = '/sections',
    QUESTION_Endpoint = '/questions'


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

var Controller = Backbone.View.extend({
  
  el : 'body',

  initialize : function(data){
    this.model = new Form_model(data.form);
  }
});

