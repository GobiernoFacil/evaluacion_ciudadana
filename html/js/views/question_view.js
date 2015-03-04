// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP
// @package  : agentes
// @location : /js/views
// @file     : question_view.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){

  var Backbone = require('backbone'),
      Question = require('text!templates/survey_question.html'),
      Option   = require('text!templates/survey_option.html'); 

  var section = Backbone.View.extend({
    events : {
    },

    tagName : 'div',

    template : _.template(Question),
    opt_temp : _.template(Option),

    initialize : function(settings){
      this.opt = settings.opt;
      this.render();
    },

    render : function(){
      this.$el.html(this.template(this.model.attributes));
      if(this.opt.length){
        this.opt.each(function(option){
          this.$el.append(this.opt_temp(option.attributes));
        }, this);
      }
      return this;
    }
  });

  return section;
});