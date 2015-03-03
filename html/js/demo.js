/* 
* TEMPLATES
* ----------------------------------------
*/

var question_template = '<p><%= question %></p><ul></ul><p><a class="add-option" href="#">agregar respuesta</p>';
var option_template   = '<form><p><label>descripción:</label><input type="text" name="description"></p><p><label>valor:</label><input type="text" name="value"></p><p><input type="submit" value="agregar"></p></form>';

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

  urlRoot : OPTIONS_Endpoint,

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
  },

  render : function(){
    this.collection.each(function(model){
      var question = new Question({model : model});
      this.$('#main').append(question.render().el);
    });
  }
});

// Question
// ---------------------------------------
//
var Question = Backbone.View.extend({
  events : {
    'click a.add-option' : 'add_option'
  },

  tagName : 'div',

  template : _.template(question_template),

  initialize : function(){
    this.collection = new Backbone.Collection([], {
      model : Option_model,
      url : OPTIONS_Endpoint
    });

    this.model.set({view : this});

    this.listenTo(this.collection, 'add', this.render_option);
  },

  render : function(){
    this.$el.append(this.template(this.model.attributes));
    return this;
  },

  render_option : function(model, collection, options){
    var opt = new Option({model : model});
    this.$('ul').append(opt.render().el);
  },

  add_option : function(e){
    e.preventDefault();

    this.collection.add({
      question_id  : this.model.id,
      blueprint_id : this.model.get('blueprint_id'),
      order_num    : this.collection.length
    });
  }
});

// Option
// ---------------------------------------
//
var Option = Backbone.View.extend({
  events : {
    'submit' : 'handle_option'
  },

  tagName : 'li',

  template : _.template(option_template),

  initialize : function(){
  },

  render : function(){
    this.$el.append(this.template(this.model.attributes));
    return this;
  },

  handle_option : function(e){
    e.preventDefault();

    var description = this.$('input[name="description"]').val();
    var value       = this.$('input[name="value"]').val();

    this.model.set({
      description : description,
      value       : value
    });
    
    if(this.model.isNew()){
      console.log('is new');
      this.model.save();
    }
    else{
      console.log('not new');
    }
  }
});


