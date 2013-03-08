/**
 * User: akira
 * Date: 30.11.12
 * Time: 18:53
 */

!function( $ ){
  "use strict"

  var Ajaxable = function ( element, options ) {
    this.$element = $(element);
    this.options = $.extend({}, $.fn.ajaxable.defaults, options);
    this.ajaxData = $.extend({}, this.options, this.$element.data());
    this.initialize();
  }
  Ajaxable.prototype = {
    constructor: Ajaxable
    , initialize : function () {
      this.$element.on(this.ajaxData.event, $.proxy(this.eventAction, this))
    }
    , eventAction : function (e) {
        e.preventDefault();
        $.proxy(this.request, this);
    }
    , request : function(){
      var that = this;
      $.ajax(this.ajaxData).done(function( msg ) {
          var target = $(that.$element.data('target'));
          if (target.length > 0)
          {
              target.replaceWith(msg);
          }
          that.$element.trigger('done', msg)
      });
    }
  }

  $.fn.ajaxable = function ( option ) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('ajaxable')
        , options = typeof option == 'object' && option
      if (!data) $this.data('ajaxable', (data = new Ajaxable(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.ajaxable.defaults = {
    event: "click",
    type: "get",
    url : "/",
    data: ""
  }

  $.fn.ajaxable.Constructor = Ajaxable

  $('*[data-ajax=true]').ajaxable()
}( window.jQuery );