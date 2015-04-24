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
      Container = require('text!templates/question_item_list.admin.html'),
      Editor    = require('text!templates/question_item.admin.html'),
      Option    = require('text!templates/option_item.admin.html');

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
      'click a.update'             : 'render_editor',
      'click a.close-editor'       : 'render', // render list view
      'click a.cancel'             : 'render_editor',
      'click li a'                 : 'remove_option',
      'click a.save'               : '_save',
      'click a.delete'             : '_suicide',
      'focus ul li input'          : '_enable_save_option',
      'blur ul li input'           : '_disable_save_option',
      'change input[type="radio"]' : 'toggle_options'
    },

    // 
    // [ SET THE CONTAINER ]
    // 
    //
    tagName : 'li',

    // -----------------
    // SET THE TEMPLATES
    // -----------------
    //
    template : _.template(Container),
    editor   : _.template(Editor),
    option   : _.template(Option),

    // ------------------------
    // THE INITIALIZE FUNCTION
    // ------------------------
    //
    initialize : function(){
      this.listenTo(this.model, 'remove', this.remove);
      this.listenTo(this.model, 'destroy', this.remove);
    },

    //
    // R E N D E R   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //

    // --------------------
    // CALL THE MAIN RENDER
    // --------------------
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
      // [0] configura algunas variables
      var options = this.model.get('options');
      // [1] usa el template
      this.$el.html(this.editor(this.model.attributes));
      this.$('.question-panel-editor').show();
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
      // [4] agrega el selector de sección
      this.render_section_selector();
      var color = this.model.get('section_id') * 15;
      this.el.style.borderRight = "5px solid hsl(" + color + ", 100%, 50%)";
      return this;
    },

    _render_options : function(options){
      this.$('.options-container').show();
      _.each(options, function(option){
        var name = _.uniqueId('lp');
        this.$('.options-container ul').append(this.option({
          name     : name, 
          value    : option.cid ? option.get('description') : option.description,
          is_first : false
        }));
      }, this);
    },

    _render_new_option : function(e){
      var is_first = e === void 0;
      if(is_first || (e.keyCode === 13 && e.target.value)){
        var name = _.uniqueId('lp');
        this.$('ul').append(this.option({
          name     : name, 
          value    : '',
          is_first : 0 // O___o
        }));

        if(! is_first) this.$('input[name="' + name + '"]')[0].focus();
      }
    },

    remove_option : function(e){
      e.preventDefault();
      this.$(e.target.parentNode).remove();
      if(! this.el.getElementsByTagName('ul')[0].children.length){
        this._render_new_option();
      }
    },

    toggle_options : function(e){
      var el = this.el.querySelectorAll('.options-container')[0];
      if(e.target.value === 'multiple'){
        el.style.display = '';
        if(! this.el.getElementsByTagName('ul')[0].children.length){
          this._render_new_option();
        }
      }else{
        el.style.display = 'none';
      }
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
    _enable_save_option : function(e){
       window.onkeyup = this._render_new_option.bind(this);
    },

    _disable_save_option : function(e){
      window.onkeyup = false;
    },

    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //
    _save : function(e){
      e.preventDefault();
      var type        = this.$el.find('input[name^="type"]:checked').val(),
          title_input = this.$el.find('input[name="question"]'),
          title       = this.$('.question_editor_question input').val(),
          section     = this.el.querySelector('.section-container select').value,
          that        = this;
      if(! title){
        title_input.addClass('error');
        return;
      }
      this.model.set({
        //section_id     : section,
        // blueprint_id   : this.model.id,
        question    : title, 
        section_id  : section,
        is_location : type === 'location',
        type        : type === 'text' || type === 'location' ? 'text' : 'number',
        options     : type !== 'multiple' ? [] : this._get_options()
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
    },

    _get_options : function(){
      var inputs = this.$('.options-container input'),
          options = [];
      _.each(inputs, function(op){
        if(op.value) options.push(op.value);
      }, this);

      return options;
    }
   

  });

  return question;
});