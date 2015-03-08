// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP
// @package  : agentes
// @location : /js/views
// @file     : section_view.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){

  //
  // L O A D   T H E   A S S E T S   A N D   L I B R A R I E S
  // --------------------------------------------------------------------------------
  //
  var Backbone   = require('backbone'),
      Question   = require('views/question_view');

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
    },

    // -----------------
    // SET THE CONTAINER
    // -----------------
    //
    tagName : 'fieldset',

    // ------------------------
    // THE INITIALIZE FUNCTION
    // ------------------------
    //
    initialize : function(settings){
      this.controller = settings.controller;
      this.questions = [];
      
    },

    //
    // R E N D E R   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
    render : function(){
      this.collection.each(function(question){
        var opt = new Backbone.Collection(this.controller.q_options.where({question_id : question.id}));
        var q = new Question({model : question, opt : opt, controller : this.controller});
        this.$el.append(q.render().el);
        this.questions.push(q);
      }, this);
      return this;
    }
  });

  return section;
});