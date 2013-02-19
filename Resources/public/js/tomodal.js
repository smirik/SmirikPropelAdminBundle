!function( $ ){
  "use strict"

  var ToModal = function ( element, options ) {
    this.$element = $(element)
    this.path = this.$element.attr('href')
    this.options = $.extend({}, $.fn.tomodal.defaults, options)
    this.$element.on('click', $.proxy(this.load, this))
    this.initialize()
  }
  ToModal.prototype = {
    constructor: ToModal
    , initialize : function () {
    }
    , load : function(e){
      var that = this
      e.preventDefault()
      $.get(this.path, function(html){
        that.createModal(html)
      }, 'html')
    }
    , createModal: function(html){
      if($('#tomodal-id').length > 0) {
        var modal = $('#tomodal-id')
      }else{
        var modal = $('<div/>').addClass('modal hide fade').attr('id','tomodal-id').appendTo(document.body)
      }
      modal.html(html).modal('show')
    }
  }

  $.fn.tomodal = function ( option ) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('tomodal')
        , options = typeof option == 'object' && option
      if (!data) $this.data('tomodal', (data = new ToModal(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.tomodal.defaults = {

  }

  $.fn.tomodal.Constructor = ToModal
}( window.jQuery )
