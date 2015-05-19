// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP CREATOR
// @package  : agentes
// @location : /js
// @file     : controller.admin.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){
  
  //
  // L O A D   T H E   A S S E T S   A N D   L I B R A R I E S
  // --------------------------------------------------------------------------------
  //
  var Backbone    = require('backbone'),
      Velocity    = require('velocity'),
      d3          = require('d3'),
      Question    = require('views/question_view.admin'),
      Description = require('views/description_view.admin'),
      Option      = require('text!templates/option_item.admin.html'),
      Section_nav = require('text!templates/section_selector.admin.html');


  //
  // I N I T I A L I Z E   T H E   B A C K B O N E   " C O N T R O L L E R "
  // --------------------------------------------------------------------------------
  //
  var controller = Backbone.View.extend({
    
    // 
    // [   DEFINE THE EVENTS   ]
    // 
    //
    events :{
      // [ SURVEY NAVIGATION ]
      'click #survey-navigation-menu a' : 'render_section',
      // [ UPDATE BLUEPRINT ]
      'focus #survey-app-title input[type="text"]'       : '_enable_save',
      'blur #survey-app-title input[type="text"]'        : '_disable_save',
      'change #survey-app-title input[name="is_closed"]' : '_update_bluprint',
      'change #survey-app-title input[name="is_public"]' : '_update_bluprint',
      'click #survey-app-title .create-survey-btn'       : '_save_csv',
      // [ ADD QUESTION ]
      'change #survey-add-question input[name="type"]' : '_set_is_type',
      'click #survey-add-buttons a.add-question'       : 'render_question_form',
      'click #survey-add-question-btn'                 : '_save_question',
      // [ ADD OPTION ]
      'click #survey-add-options li a'  : '_remove_option',
      'focus #survey-add-options input' : '_enable_save_option',
      'blur #survey-add-options input'  : '_disable_save_option',
      // [ ADD HTML ] 
      'click #survey-add-buttons a.add-text' : 'render_content_form',
      'click #survey-add-content-btn'        : '_save_content',
      // [ ADD RULE ]
      'change #survey-navigation-rules-container .select-question' : '_render_rules_panel_answers',
      'click #survey-navigation-rules-container .add-rule-btn'     : '_save_rule',
      'click #survey-navigation-rules-container .remove-rule-btn'  : '_remove_rule',
      // [ CREATE CSV ]
      'click .create-survey-btn' : '_generate_csv', 
      // [ UPLOAD RESULTS ]
      'change #results-file' : '_upload_results'
    },

    // 
    // [ SET THE CONTAINER ]
    //
    //
    el : 'body',

    // 
    // [ THE TEMPLATES ]
    //
    //
    sec_nav_template : _.template(Section_nav),
    answer_template  : _.template(Option),

    //
    // [ THE INITIALIZE FUNCTION ]
    //
    //
    initialize : function(){

      // [ THE MODEL ]
      this.model         = new Backbone.Model(SurveySettings.blueprint);
      this.model.url     = "/index.php/surveys/blueprint/update";
      this.model.set({current_section : 0}); // show all
      // [ THE COLLECTION ]
     this.collection            = new Backbone.Collection(SurveySettings.questions);
     this.collection.url        = '/index.php/surveys/question';
     this.collection.comparator = function(m){ return Number(m.get('section_id'));};
     this.collection.sort();
      // [ THE OTHER COLLECTIONS ]
      this.sub_collection = new Backbone.Collection([]);
      this.q_options      = new Backbone.Collection(SurveySettings.options);
      //this.sections       = new Backbone.Collection(SurveySettings.sections);
      this.rules          = new Backbone.Collection(SurveySettings.rules);
      this.rules.url      = '/index.php/surveys/rule';
      // [ MAP THE OPTIONS FOR EACH QUESTION ]
      this.collection.each(function(el, ind, col){
        el.set({
          options : this.q_options.where({question_id : el.id})
        });
      }, this);
      // [ FIX THE SCOPES ]
      this._update_title      = $.proxy(this._update_title, this);
      this._render_new_option = $.proxy(this._render_new_option, this);
      // [ THE LISTENERS ]
      this.listenTo(this.model, 'sync', this._render_saved_title);
      this.listenTo(this.sub_collection, 'add', this._render_question);
      this.listenTo(this.collection, 'remove', this._remove_question);
      // this.listenTo(this.rules, 'add', this._render_rule);
      // [ FETCH SHORTCUTS ]
      this.html = {
        navigation_menu : this.$('#survey-navigation-menu'),
        question_form   : this.$('#survey-add-question'),
        content_form    : this.$('#survey-add-content'),
        answers_form    : this.$('#survey-add-options')
      };
      // [ RENDER ]
      this.render();
    },

    //
    // R E N D E R   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //

    // [ THE RENDER ]
    //
    //
    render : function(){
      // [1] agrega el título al campo de input y asigna el valor a los
      //     checkboxs "is_public", "is_closed"
      var container = document.getElementById('survey-app-title'),
          model     = this.model.attributes;

      container.querySelector('input[type="text"]').value        = model.title;
      container.querySelector('input[name="is_closed"]').checked = Number(model.is_closed);
      container.querySelector('input[name="is_public"]').checked = Number(model.is_public);

      // [2] agrega todas las preguntas a la lista. Esta función ejecuta lo siguiente:
      //     - this.model.set({current_section : section});
      //     - this.sub_collection.set(questions);
      //     - this.render_section_menu();
      this.render_section(0);
    },

    // [ RENDER QUESTIONS FROM A SECTION ]
    //
    //
    render_section : function(e){
      if(typeof e !== "number") e.preventDefault();
      // [1] genera las variables de inicio
      var section   = typeof e === "number" ? String(e) : e.currentTarget.getAttribute('data-section'),
          coll      = this.collection,
          questions = section === "0" ? coll.models : coll.where({section_id : section});
      // [2] actualiza la sección en el modelo de la app
      this.model.set({current_section : section});
      // [3] crea la lista de preguntas
      this.sub_collection.set(questions);
      // [4] genera el navegador de secciones. Esta función ejectua lo siguiente:
      //     - this._render_rules_panel();
      this.render_section_menu();
    },

    // [ RENDER SECTION MENU ]
    //
    //
    render_section_menu : function(){
      // 
      // [1] genera las variables de inicio
      var menu     = document.getElementById('survey-navigation-menu'),
          nav      = document.getElementById('survey-app-navigation'),
          sections = _.uniq(this.collection.pluck('section_id')),
          section  = this.model.get('current_section'),
          content  = '';
      // [2] si hay menos de dos secciones, el menú para navegar
      //     entre secciones desaparece
      if(sections.length < 2){
        nav.style.display = "none";
        return;
      }
      // [3] Si hay más de una sección, el menú se hace visible, 
      //     y se le agrega un cero al inicio de la lista de secciones
      //     para que represente la opción de mostrar todas las preguntas.
      //     El contenido del <ul> se vacía para generar de nuevo cada sección.
      nav.style.display = "";
      sections.unshift(0);
      menu.innerHTML = "";

      // [4] Se genera el contenido del <ul> que contiene los links para 
      //     ver las preguntas de cada sección, y se inserta al DOM
      content += "<li>Ver secciones:</li>";
      for(var  i = 0; i < sections.length; i++){
        content += "<li><a href='#' data-section='" 
                + sections[i] +"'>" 
                + (sections[i] ? sections[i] : 'todas') + "</a></li>"
      }
      menu.innerHTML = content;

      // [5] limpia la lista de la clase "current", para después asignarla a
      //     la sección actual en el anchor
      this.$('#survey-navigation-menu a').removeClass('current');
      this.$('#survey-navigation-menu a[data-section="' + section + '"]').addClass('current');

      // [6] genera el menú para crear/ver las reglas de navegación
      this._render_rules_panel();
    },

    // [ RENDER RULES PANEL ]
    //
    //
    _render_rules_panel : function(){
      // [0] obtiene la referencia de los elementos a ocupar
      var menu     = document.getElementById('survey-navigation-rules-container'),
          list     = document.getElementById('survey-navigation-rules'),
          q_select = menu.querySelector('.select-question'),
      // [1] crea las variables de inicio
          section          = this.model.get('current_section'),
          rules            = this.rules.where({section_id : section}),
          sections         = _.uniq(this.collection.pluck('section_id')),
          low_sections     = null,
          low_questions    = [],
          q_select_content = "";
      // [2] vacía la lista de reglas
      list.innerHTML = "";
      // [3] agrega la lista de reglas existentes
      _.each(rules, function(rule){
        this._render_rule(rule);
      }, this);
      // [4] obtiene las secciones anteriores a la actual para 
      //     buscar en ellas preguntas de opción múltiple de las
      //     cuales pueda depender si se ve o no.
      low_sections = _.filter(sections, function(sec){
        return Number(sec) < Number(section);
      }, this);
      // [5] busca preguntas de opción múltiple de secciones
      //     anteriores para el <select> de crear nueva regla.
      _.each(low_sections, function(section_id){
        questions = this.collection.where({
          is_description : '0', 
          section_id     : section_id, 
          type           : "number"
        });
        Array.prototype.push.apply(low_questions, questions);
      }, this);
      // [6] si hay preguntas de opción múltiple anteriores,
      //     llena el <select> de preguntas
      if(low_questions.length){
        this.$('.rule-answer').remove();
        _.each(low_questions, function(q){
          q_select_content += "<option class='rule-answer' value='" + q.id +"'>" + q.get('question') + "</option>";
        },this);

        this.$(q_select).append(q_select_content);
        menu.style.display = "";
      }
      // [7] si no hay preguntas de opción múltiple anteriores,
      //     se oculta el menú de reglas
      else{
        menu.style.display = "none";
      }
    },

    // [ ADD NEW RULE TO THE LIST ]
    //
    //
    _render_rule : function(model){
      // [1] método larguísmo para agregar un <li>!!!!!!
      var ul       = document.getElementById('survey-navigation-rules'),
          li       = document.createElement('li'),
          anchor   = document.createElement('a'),
          q_id     = model.get('question_id'),
          question = this.collection.get(q_id),
          q_text   = question.get('question'),
          option   = _.find(question.get('options'), function(m){
            return (m.value || m.get('value')) == model.get('value');
          }, this),
          o_text   = option.description || option.get('description'),
          text     = document.createTextNode(q_text + ' | R= ' + o_text);

          anchor.innerHTML = "x";
          anchor.setAttribute('class', 'remove-rule-btn');
          anchor.setAttribute('href', '#');
          anchor.setAttribute('data-rule', model.id);

          li.appendChild(text);
          li.appendChild(anchor);
          ul.appendChild(li);
    },

    // [ RENDER THE ANSWERS <SELECT> FOR THE RULES ]
    //
    //
    _render_rules_panel_answers : function(e){
      var question_id  = e.target.value,
          question     = this.collection.get(question_id),
          answers      = document.querySelector('#survey-add-navigation-rule .select-answer'),
          answers_list = '';
      if(question){
        if(question.attributes.options.length){
          _.each(question.attributes.options, function(option){
            answers_list += "<option class='rule-answer-option' value='" 
                         + (option.value || option.get('value')) +"'>" 
                         + (option.description || option.get('description')) + "</option>";
          }, this);
          answers.innerHTML = answers_list;
          answers.style.display = "";
        }
      }
      else{
        answers.style.display = "none";
      }
    },

    // [ RENDER SINGLE QUESTION ]
    //
    //
    _render_question : function(model, collection){
      var container = this.$('#survey-question-list'),
          is_description = Number(model.get('is_description')),
          item = ! is_description ? new Question({model : model}) : new Description({model : model});
      container.append(item.render().el);
      this.render_section_menu();
    },

    // [ SHOW THE ADD QUESTION FORM ]
    //
    //
    render_question_form : function(e){
      e.preventDefault();
      var q_form = this.html.question_form[0].querySelector('.survey-section-selector-container'),
          c_form = this.html.content_form[0].querySelector('.survey-section-selector-container');

      var selector = c_form.querySelector('.survey-section-selector');
      if(selector){
        q_form.appendChild(c_form.removeChild(selector));
      }

      this.html.question_form.find('input[value="text"]')[0].checked = true;
      this.html.content_form.hide();
      this.html.question_form.show();

      if(this.collection.length){
        this.render_section_selector();
      }
    },

    // [ SHOW THE SECTION SELECTOR ]
    //
    //
    render_section_selector : function(){
      var sections = _.uniq(this.collection.pluck('section_id')),
          data     = [],
          box      = this.el.querySelector('.survey-section-selector'),
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
        content +="<option value='" + data[i].value + "'>" + data[i].text + "</option>";
      }
      el.innerHTML = content;
      el.children[el.children.length - 2].selected = true;
    },

    // [ SHOW THE ADD HTML FORM ]
    //
    //
    render_content_form : function(e){
      e.preventDefault();
      var q_form = this.html.question_form[0].querySelector('.survey-section-selector-container'),
          c_form = this.html.content_form[0].querySelector('.survey-section-selector-container');

      var selector = q_form.querySelector('.survey-section-selector');
      if(selector){
        c_form.appendChild(q_form.removeChild(selector));
      }

      this.html.content_form.show();
      this.html.answers_form.hide();
      this.html.question_form.hide();

      if(this.collection.length){
        this.render_section_selector();
      }
    },

    // [ ADD NEW ANSWER OPTION ]
    //
    //
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

    // [ RESTORE DEFAULT LOOKS TO THE ADD QUESTION FORM ]
    //
    //
    clear_question_form : function(){
      var form    = this.html.question_form[0],
          options = document.getElementById('survey-add-options'),
          keep_op = document.getElementById('keep-options'),
          ul      = options.querySelector('ul');
      
      if(! keep_op.checked){
         while(ul.children.length > 1){
          ul.removeChild(ul.children[1]);
        }
      }
      
      if(ul.children.length){
        ul.children[0].querySelector('input').value = "";
      }
      form.querySelector('input[name="question"]').value = "";
    },

    // [ SHOW THE LOADING STATUS: TITLE ]
    //
    //
    _render_saving_title : function(e){

    },

    // [ SHOW THE SUCCESS STATUS: TITLE ]
    //
    //
    _render_saved_title : function(model, response, options){
    },

    //
    // I N T E R A C T I O N
    // --------------------------------------------------------------------------------
    //

    // [ ADD A LISTENER TO THE ENTER BTN ]
    //
    //
    _enable_save : function(e){
      window.onkeyup = this._update_title;
    },

    // [ REMOVE THE LISTENER TO THE ENTER BTN ]
    //
    //
    _disable_save : function(e){
      window.onkeyup = false;
      this._update_title();
    },

    _enable_save_option : function(e){
      window.onkeyup = this._render_new_option;
    },

    _disable_save_option : function(e){
      window.onkeyup = false;
    },

    _remove_option : function(e){
      e.preventDefault();
      this.$(e.currentTarget).parent().remove();
    },

    _set_is_type : function(e){
      var container = this.html.answers_form.children('ul')[0];
      if(e.currentTarget.value === 'multiple'){
        if(! container.getElementsByTagName('li').length){
          this.$(container).append(this.answer_template({
            name     : _.uniqueId('lp'), 
            value    : '',
            is_first : 1
          }));
        }
        this.html.answers_form.show();
      }
      else{
        this.html.answers_form.hide();
      }
    },




    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //

    // [ UPDATE TITLE ] 
    //
    //
    _update_title : function(e){
      if(e === void 0 || e.keyCode === 13){
        this._update_bluprint();
      }
    },

    // [ UPDATE BLUEPRINT ]
    //
    //
    _update_bluprint : function(e){
      var container = document.getElementById('survey-app-title'),
          is_closed = container.querySelector('input[name="is_closed"]').checked,
          is_public = container.querySelector('input[name="is_public"]').checked,
          title     = container.querySelector('input[type="text"]').value;
      
      if(title) this.model.set({title : title});
      this.model.set({
        is_public : is_public,
        is_closed : is_closed,
      });

      this.model.save();
    },

    // [ CREATE THE CSV ]
    // 
    //
    _save_csv : function(e){

    },

    // [ SAVE QUESTION ] 
    //
    //
    _save_question : function(e){
      e.preventDefault();
      var form        = this.html.question_form[0],
          type        = form.querySelector('input[name="type"]:checked').value,
          title_input = form.querySelector('input[name="question"]'),
          title       = title_input.value,
          section     = this.el.querySelector('.survey-section-selector select').value,
          question    = new Backbone.Model(null, {collection : this.collection}),
          that        = this;
      if(! title){
        title_input.addClass('error');
        return;
      }
      question.set({
        section_id     : section,
        blueprint_id   : this.model.id,
        question       : title, 
        is_description : 0,
        is_location    : type === 'location',
        type           : type === 'text' || type === 'location' ? 'text' : 'number',
        options        : type !== 'multiple' ? [] : this._get_options()
      });

      question.save(null, {
        success : function(model, response, options){
          that.collection.add(model);
          that.render_section_selector();
          that.clear_question_form();
          that.render_section(Number(model.get('section_id')));
        }
      });
    },

    // [ REMOVE QUESTION ]
    //
    //
    _remove_question : function(){
      this.render_section_selector();
    },

    // [ SAVE CONTENT ] 
    //
    //
    _save_content : function(e){
      e.preventDefault();
      var html    = this.html.content_form.find('textarea').val(),
          section = this.$('.survey-section-selector select').val(),
          content = new Backbone.Model(null, {collection : this.collection}),
          that    = this;

      if(! html){
        this.html.content_form.find('textarea').addClass('error');
        return;
      }

      content.set({
        section_id     : section,
        blueprint_id   : this.model.id,
        question       : html, 
        is_description : 1,
        is_location    : 0,
        type           : 'text',
        options        : []
      });

      content.save(null, {
        success : function(model, response, options){
          that.collection.add(model);
          that.render_section_selector();
          //that.render_section(that.model.get('current_section'));
          that.html.content_form[0].querySelector('textarea').value = "";
          that.render_section(0);
        }
      });
    },

    // [ SAVE RULE ]
    //
    //
    _save_rule : function(e){
      e.preventDefault();
      var container   = document.querySelector('#survey-add-navigation-rule'),
          question_id = container.querySelector('.select-question').value,
          value       = container.querySelector('.select-answer').value,
          section_id  = this.model.get('current_section'),
          rule        = new Backbone.Model(null, {collection : this.rules}),
          that        = this;

      if(!Number(question_id)) return;
      
      rule.set({
        section_id  : section_id,
        question_id : question_id,
        value       : value
      });

      rule.save(null, {
        success : function(model, response, options){
          that.rules.add(model);
          that._render_rules_panel();
        }
      });
    },

    // [ REMOVE RULE ]
    //
    //
    _remove_rule : function(e){
      e.preventDefault();
      var rule_id = e.target.getAttribute('data-rule'),
          li      = e.target.parentNode;
      this.rules.get(rule_id).destroy({
        success : function(m, response){
          li.parentNode.removeChild(li);
        }
      });
    },

    // [ GET QUESTION OPTIONS AS AN ARRAY ] 
    //
    //
    _get_options : function(){
      var inputs = this.$('#survey-add-options input[type="text"]'),
          options = [];
      _.each(inputs, function(op){
        if(op.value) options.push(op.value);
      }, this);
      return options;
    },

    // [ GENERATE CSV ]
    //
    //
    _generate_csv : function(e){
      e.preventDefault();
      $.post('/index.php/surveys/make-csv', {}, function(data){
        var anchor     = document.getElementById('get-csv-btn'),
            create_btn = document.querySelector('.create-survey-btn');

        create_btn.style.display = 'none';   
        anchor.href = data.full_path;
        anchor.innerHTML = "generando CSV";
        anchor.setAttribute('target', '_blank');
        anchor.style.display = "";
      }, 'json');
    },

    // [ UPLOAD RESULTS ]
    //
    //
    _upload_results : function(e){
      // [1] define las variables necesarias
      var files  = e.target.files,
          fData  = new FormData(),
          xhr    = new XMLHttpRequest(),
          url    = "/index.php/surveys/upload-results",
          name   = "results",
          anchor = document.getElementById('get-csv-btn'),
          waitlb = document.getElementById('sending-label'),
          btn    = document.getElementById('send-file-button'),
          file;
      // [2] si no se seleccinó ningún archivo, pelas.
      if(!files.length) return;

      // [3] en caso de tener un archivo seleccionado, lo envía al
      //     servidor mediante AJAX. Al enviar el archivo, el botón 
      //     para seleccionar documento se oculta y aparece el letrero de
      //     "enviando archivo".
      file = files[0];
      fData.append(name, file);
      xhr.open('post', url, true);
      xhr.onload = function(data){
        response = JSON.parse(xhr.responseText);
        if(response.success){
          anchor.href = "/csv/" + response.name;
          anchor.innerHTML = "descargar archivo";
          anchor.setAttribute('target', '_blank');
          anchor.style.display = "";
        }
        waitlb.style.display = "none";
        btn.style.display    = "";
      }
      xhr.send(fData);
      waitlb.style.display = "";
      btn.style.display    = "none";
      // sending-label
      // send-file-button
      // get-csv-btn
    }

  });

  return controller;
});