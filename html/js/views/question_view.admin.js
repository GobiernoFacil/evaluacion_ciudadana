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
      Container = require('text!templates/question_item.admin.html'),
      Option    = require('text!templates/option_item.admin.html');

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
      'click a.update' : 'open_editor'
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
    option   : _.template(Option),

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
      // [0] configura algunas variables
      var options = this.model.get('options');
      // [1] usa el template
      this.$el.append(this.template(this.model.attributes));
      // [2] selecciona el tipo de pregunta
      if(Number(this.model.get('is_location'))){
        this.$('input[value="location"]')[0].checked = 1;
      }
      else if(this.model.get('type') === 'text'){
        this.$('input[value="text"]')[0].checked = 1;
      }
      else if(options.length){
        this.$('input[value="multiple"]')[0].checked = 1;
      }
      else{
        this.$('input[value="number"]')[0].checked = 1;
      }

      // [3] agrega las secciones, si se necesita
      if(options.length){
        this._render_options(options);
      }

      return this;
    },

    _render_options : function(options){
      this.$('.options-container').show();
      _.each(options, function(option){
        var name = _.uniqueId('lp');
        this.$('.options-container ul').append(this.option({
          name     : name, 
          value    : option.get('description'),
          is_first : false
        }));
      }, this);
    },

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
    open_editor : function(e){
      e.preventDefault();
      this.$('div.question-panel-editor').show();
      console.log(this.model.attributes);
    }


    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
   

  });

  return section;
});