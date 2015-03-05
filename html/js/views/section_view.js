// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP
// @package  : agentes
// @location : /js/views
// @file     : section_view.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){

  var Backbone   = require('backbone'),
      Question   = require('views/question_view');

  var section = Backbone.View.extend({
    events : {
    },

    tagName : 'fieldset',

    initialize : function(settings){
      this.controller = settings.controller;
      this.questions = [];
    },

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