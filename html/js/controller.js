// AGENTES DE INNOVACIÓN - TÚ EVALÚAS APP
// @package  : agentes
// @location : /js
// @file     : controller.js
// @author  : Gobierno fácil <howdy@gobiernofacil.com>
// @url     : http://gobiernofacil.com

define(function(require){
  
  //
  // L O A D   T H E   A S S E T S   A N D   L I B R A R I E S
  // --------------------------------------------------------------------------------
  //
  //  Backbone: Backbone.js
  var Backbone = require('backbone'),
  //  Section: es un view que contiene la lógica y HTML de cada sección.
  //  Este script carga a su vez un "view" llamado question_view.js, que 
  //  Se encarga de generar el HTML de cada pregunta y su conexión con el servidor.
      Section  = require('views/section_view');

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
      'submit #survey' : '_do_nothing'
    },

    // 
    // [ SET THE CONTAINER ]
    //
    //
    el : 'body',

    //
    // [ THE INITIALIZE FUNCTION ]
    //
    //
    initialize : function(){

      // [ THE MODEL ]
      // inicia el modelo con el id del bluprint, el identificador
      // del formulario y el título. agentesFormSettings se define en
      // el HTML que carga este script (mediante require.js)
      this.model = new Backbone.Model({
        id    : agentesFormSettings.id,
        key   : agentesFormSettings.key, 
        title : agentesFormSettings.title
      });

      // [ THE COLLECTION ]
      // se inicia la colección con todas las preguntas del
      // formulario
      this.collection = new Backbone.Collection(agentesFormSettings.questions);

      // [ THE OTHER COLLECTIONS ]
      // aquí se incluye la lista de opciones (y tal vez respuestas)
      // de todas las preguntas. También las secciones en las que está
      // dividido el cuestionario y las reglas que se aplican para navegar
      // en él.
      this.q_options = new Backbone.Collection(agentesFormSettings.options);
      this.answers   = new Backbone.Collection(agentesFormSettings.answers);
      this.rules     = new Backbone.Collection(agentesFormSettings.rules);
      this.sections  = [];
      this._define_nav_rules();

      // [ UPDATE THE COLLECTION ]
      // si la pregunta ya fue contestada, se le asigna un valor por default;
      // este valor viene directo del servidor, y no se guarda nuevamente, a menos
      // que la respuesta se actualice. El valor de la respuesta también sirve para 
      // saber si el usuario ha terminado de contestar cada sección.
      this._update_questions();

      // [ GENERATE SECTIONS ]
      // cada sección es un paso en la navegación del formulario.
      this._create_sections();

      // [ THE POINTER ]
      // lleva registro de dónde va el la navegación del formulario
      this.navigation_pointer = 0;

      // [ RENDER ]
      // genera el HTML para el formulario
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
      // [ THE FIRST SECTION ]
      // [1] dibuja solo la primera sección del formulario
      this.$('#survey').append(this.sections[0].el);
      // [2] por si las flys, actualiza el pointer
      this.navigation_pointer = 0;
    },

    // [ RENDER NEXT SECTION ]
    //
    //
    render_next : function(){
      // [ SOME VALIDATION ]
      // revisa si el usuario ha contestado todas las preguntas
      // antes de pasar a la siguiente sección. Si le falta alguna,
      // se le indica mediante un recuadro rosa que le falta contestar
      // esa pregunta. Porque #YOLO
      var errors = this._validate_section();
      if(errors.length){
        _.each(errors, function(view){
          view.el.style.border = "1px solid #FF6F69";
        });
        return false;
      }
      // [ THE n SECTION ]
      // dibuja la siguiente sección, siempre y cuando exista!
      // [1] obtiene la siguiente posición del formulario
      var position = this.navigation_pointer + 1;

      // [2] revisa que la nueva posición sea válida!
      if(position < this.sections.length){
      // [3] obtiene los requisitos para la siguiente sección
        var rules = this.nav_rules[position];
      // [4] si hay requisitos, revisa que se cumplan, y si no,
      //     que cargue los requisitos de la siguiente sección
      //     y así hasta llegar a una sección que cumpla los requisitos
      //     o al final del formulario
        while(rules){
        // [4.1] revisa si se cumplen las condiciones. las condiciones pueden ser
        //       un objeto, en caso de que solo dependa de una pregunta, o un array,
        //       en caso de que dependa de varias preguntas. Si son varias preguntas,
        //       debe cumplir con todas, o no se muestra la sección.
          var condition = false;
          if(_.isArray(rules)){
            condition = true;
            for(var i = 0; i < rules.length; i++){
              var question = this.collection.findWhere({id : rules[i].question});
              var value    = question.get('default_value');
              condition    = rules[i].val.indexOf(value) > -1;
              if(!condition) break;
            }
          }
          else{
            var question = this.collection.findWhere({id : rules.question});
            var value    = question.get('default_value');
            condition    = rules.val.indexOf(value) > -1
          }

          if(condition){
            break;
          }
        // [4.2] si el valor no coincide, pero no hay más preguntas,
        //       también termina el ciclo y renderea la última sección 
          else if(position + 1 >= this.sections.length){
            break;
          }
        // [4.3] si el valor no coincide, pero hay más secciones, mueve
        //       el pointer y revisa de nuevo
          else{
            position++;
            this.navigation_pointer++;
            rules = this.nav_rules[position];
          }
        }
      // [5] hace hueco para el siguiente contenido.
      //     aquí es donde podría haber "magia" en la interacción
        this.$('#survey').html('');
      // [6] renderea la siguiente sección
        this.$('#survey').append(this.sections[position].el);

      // [6.1] HACK: quita el botón de siguiente al llegar al final de formulario
        if(this.sections[position].pos +1 == this.sections.length) this.$('a.next').remove();

      // [7] actualiza el pointer
        this.navigation_pointer++;

      }
    },

    //
    // I N T E R N A L   F U N C T I O N S 
    // --------------------------------------------------------------------------------
    //

    // [ SET NAVIGATION RULES ]
    //
    //
    _define_nav_rules : function(){
      // [ THE NAV RULES]
      // con esta guía, no se muestran todos los páneles, solo
      // los que concuerden con la navegación.
      // hace falta definir algo similar para validar las respuestas.

      // [1] obtiene una lista de las secciones existentes.
      var sections  = _.uniq(this.collection.pluck('section_id'));

      // [2] inicia un array que contendrá las reglas de navegación
      //     para cada sección.
      this.nav_rules = [];

      // [3] para cada sección, busca si existen reglas que aplicar.
      //     si no hay ninguna regla, pasa el valor de NULL para la sección.
      _.each(sections, function(section){
        var rules = this.rules.where({section_id : section});
        if(rules.length){
      // [3.1] revisa de cuántas preguntas depende la sección. Si solo depende
      //       de una pregunta, regresa un objeto, si depende de varias, regresa
      //       un arreglo. Por ahora, si depende de una sola pregunta, hace
      //       obligatoria la respuesta que se le pasa; si depende de una pregunta
      //       pero varias posibles respuestas, se requiere que por lo menos una sea
      //       la seleccionada; si depende de varias preguntas, se deben cumplir todas
      //       las respuestas esperadas. Es decir, "OR" para respuestas de la misma
      //       pregunta, y "AND" para varias preguntas. 
          var r_collection = new Backbone.Collection(rules);
          var questions_id = _.uniq(r_collection.pluck('question_id'));
      // [3.2] cada regla contiene la pregunta a la que pertenece y un arreglo
      //       con las reglas que debe aplicar
          if(questions_id.length < 2){
            this.nav_rules.push({
              question : rules[0].get('question_id'),
              val      : _.map(rules, function(rule){return rule.get('value');})
            });
          }
          else{
            var section_rules = [];
            _.each(questions_id, function(id){
              var r = r_collection.where({question_id : id});
              section_rules.push({
                question : id,
                val      : _.map(r, function(rule){return rule.get('value');})
              });
            }, this);
            this.nav_rules.push(section_rules);
          }
        }
        else{
          this.nav_rules.push(null);
        }
      }, this);
    },

    // [ CREATE SECTION ]
    //
    //
    _create_sections : function(){
      // [ THE SECTIONS ]
      // A partir de la colección de preguntas, genera una lista de secciones.
      // Con esta lista llena un Array (this.sections) de Views, uno por cada sección.

      // [1] obtiene la lista de secciones de la colección de preguntas 
      var sections  = _.uniq(this.collection.pluck('section_id'));

      // [2] para cada sección, genera un view (Section), que incluye una 
      //     colección de preguntas y una referencia al controller (por si ocupa).
      _.each(sections, function(section, pos){
        var collection = new Backbone.Collection(this.collection.where({section_id : section}));
        this.sections.push(new Section({collection : collection, controller : this, pos : pos}));
      }, this);
    },

    // [ UPDATE QUESTIONS ]
    //
    //
    _update_questions : function(){
      // Esta función se ejecuta al inicio del cuestionario, para asignar 
      // los valores disponibles a las preguntas ya contestadas, en caso de
      // que se vuelva a acceder al mismo.
      this.collection.each(function(question){
        // [ THE ANSWER ]
        // para cada pregunta, revisa si ya ha sido contestada. Si no ha
        // sido contestada, se le asigna un valor de NULL a la propiedad
        // "default_value". Si ya ha sido respondida, se le asigna el valor
        // actual del servidor.

        // [1] busca la respuesta en la colección "answers" de este controller
        var answer = this.answers.findWhere({question_id : question.id});

        // [2] si la respuesta existe, se asigna el valor text/num a "default_value"
        if(typeof answer !== "undefined"){
          question.set({default_value : answer.get('num_value') || answer.get('text_value')});
        }

        // [3] si no se ha respondido, se asigna el valor NULL a "default_value"
        else{
          question.set({default_value : null});
        }

      }, this);
    },

    // [ VALIDATE SECTION ]
    //
    //
    _validate_section : function(){
      var questions = this.sections[this.navigation_pointer].questions,
          errors    = [];

      _.each(questions, function(question){
        var value = question.model.attributes.default_value,
            is_description = question.model.attributes.is_description;

        if(!value && is_description !=="1") errors.push(question);
      }, this);

      return errors;
    },

    // [ DO NOTHING ]
    //
    //
    _do_nothing : function(e){
      // [ DO NOTHING ]
      // Esta función se ejecuta cuando se envía el formulario. Dado que es
      // un sistema que se actualiza mediante ajax, no es necesario en ningún
      // momento que el formulario se envíe.
      e.preventDefault();
    }

  });

  return controller;
});