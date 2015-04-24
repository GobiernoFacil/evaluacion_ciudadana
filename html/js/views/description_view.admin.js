// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP CREATOR
// @package  : agentes
// @location : /js/views
// @file     : description_view.admin.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){

  //
  // L O A D   T H E   A S S E T S   A N D   L I B R A R I E S
  // --------------------------------------------------------------------------------
  //
  var Backbone  = require('backbone'),
      Container = require('text!templates/description_item_list.admin.html'),
      Editor    = require('text!templates/description_item.admin.html');

  //
  // I N I T I A L I Z E   T H E   B A C K B O N E   V I E W
  // --------------------------------------------------------------------------------
  //
  var question = Backbone.View.extend({

    // 
    // [ DEFINE THE EVENTS ]
    // 
    //
    events : {
      'click a.update'       : 'render_editor',
      'click a.close-editor' : 'render',
      'click a.cancel'       : 'render_editor',
      'click a.save'         : '_save',
      'click a.delete'       : '_suicide'
    },

    // 
    // [ SET THE CONTAINER ]
    // 
    //
    tagName : 'li',

    // 
    // [ SET THE TEMPLATES ]
    // 
    //
    template : _.template(Container),
    editor   : _.template(Editor),

    //
    // [ THE INITIALIZE FUNCTION ]
    //
    //
    initialize : function(){
      this.listenTo(this.model, 'remove', this.remove);
      this.listenTo(this.model, 'destroy', this.remove);
    },

    //
    // R E N D E R   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //

    // 
    // [ CALL THE MAIN RENDER ]
    // 
    //
    render : function(e){
      if(e !== void 0) e.preventDefault();
      this.$el.html(this.template(this.model.attributes));

      var color = this.model.get('section_id') * 15;
      this.el.style.borderRight = "5px solid hsl(" + color + ", 100%, 50%)";
      return this;
    },

    render_editor : function(e){
      if(e !== void 0) e.preventDefault();
      this.el.innerHTML = this.editor(this.model.attributes);

      this.render_section_selector();

      var color = this.model.get('section_id') * 15;
      this.el.style.borderRight = "5px solid hsl(" + color + ", 100%, 50%)";
      return this;
    },

    // [ SHOW THE SECTION SELECTOR ]
    //
    //
    render_section_selector : function(){
      var sections = _.uniq(this.model.collection.pluck('section_id')),
          data     = [],
          box      = this.el.querySelector('.section-container'),
          el       = box.querySelector('select'),
          content  = '',
          i;
          box.style.display = '';
      if(!sections){
        data.push({text : 'sección 1', value : 1});
      }
      else{
        if(sections.length >= 2){
          sections = sections.sort(function(a,b){
            return a-b;
          });
        }
        for(i = 1; i<= sections.length; i++){
          data.push({text : 'sección ' + sections[i-1], value : sections[i-1]});
        }
      }
      data.push({text : 'nueva sección', value : Number(sections[sections.length-1]) + 1});

      for(i = 0; i < data.length; i++){
        content +="<option value='" + data[i].value + "' " 
                + (this.model.get('section_id') == data[i].value ? 'selected' : '') 
                + ">" + data[i].text + "</option>";
      }
      el.innerHTML = content;
    },

    //
    // I N T E R A C T I O N
    // --------------------------------------------------------------------------------
    //


    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
    _save : function(e){
      e.preventDefault();
      var content = this.el.getElementsByTagName('textarea')[0],
          section = this.el.querySelector('.section-container select').value,
          that    = this;
      if(! content.value){
        if (content.classList){
          el.classList.add('error');
        }
        else{
          el.className += ' ' + 'error';
        }
        return;
      }

      this.model.set({
        question   : content.value,
        section_id : section 
      });
      this.model.save(null, {
        success : function(model, response, options){
          that.render_editor();
        }
      });
    },

    _suicide : function(e){
      e.preventDefault();
      this.model.destroy({wait: true});
    }
   

  });

  return question;
});