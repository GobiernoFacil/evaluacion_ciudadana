// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP
// @package  : agentes
// @location : /js/views
// @file     : question_view.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){

  var Backbone   = require('backbone'),
      Question   = require('text!templates/survey_question.html');

  var section = Backbone.View.extend({
    events : {
    },

    tagName : 'div',

    template : _.template(Question),

    initialize : function(){
      this.render();
    },

    render : function(){
      this.$el.html(this.template(this.model.attributes));
      return this;
    }
  });

  return section;
});