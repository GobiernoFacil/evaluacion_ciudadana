// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP
// @package  : agentes
// @location : /js/views
// @file     : question_view.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){

  var Backbone = require('backbone'),
      Description = require('text!templates/survey_description.html'),
      Question    = require('text!templates/survey_question.html'),
      Option      = require('text!templates/survey_option.html'),
      Input       = require('text!templates/survey_input.html');

  var section = Backbone.View.extend({
    events : {
    },

    tagName : 'div',

    template : _.template(Question),
    des_temp : _.template(Description),
    opt_temp : _.template(Option),
    inp_temp : _.template(Input),

    initialize : function(settings){
      this.opt = settings.opt;
      this.render();
    },

    render : function(){
      // [ THE QUESTION ]
      // escribe dentro de un <p> la pregunta y agrega un <ul>
      // para agregar una lista de posibles respuestas. Si es 
      // una descripción, agrega un <div> en lugar del <p>
      // y no incluye un <ul>
      if(Number(this.model.get('is_description'))){
        this.$el.html(this.des_temp(this.model.attributes));
      }
      else{
        this.$el.html(this.template(this.model.attributes));
      }

      // [ THE OPTIONS ]
      // [ A ] Si hay opciones disponibles, las pone como una lista
      //       de <radio>
      if(this.opt.length){
        this.opt.each(function(option){
          this.$el.append(this.opt_temp(option.attributes));
        }, this);
      }

      // [ B ] Si no hay opciones, pero no es descripción, agrega
      //       un <input> para texto. Un elemento de "pregunta" en la DB
      //       también puede ser HTML con algún texto que describa parte
      //       del proceso
      else if(! Number(this.model.get('is_description'))){
        this.$el.append(this.inp_temp(this.model.attributes));
      }

      // [ D ] Se trata de una descripción, no hay que hacer nada
      //       (por ahora)
      else{
        //
      }

      return this;
    }
  });

  return section;
});