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
      Container = require('text!templates/question_item.admin.html');

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
      this.$el.append(this.template(this.model.attributes));
      return this;
    },

    //
    // I N T E R A C T I O N
    // --------------------------------------------------------------------------------
    //
    open_editor : function(e){
      e.preventDefault();
      this.$('div').show();
    }


    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
   

  });

  return section;
});