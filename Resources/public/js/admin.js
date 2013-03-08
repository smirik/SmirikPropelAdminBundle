$(document).on('ready', function(e){
    
    $(document).on("click", ".sortable", function (e) {
        e.preventDefault();
        linkToContent(this);
    });
    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        linkToContent(this);
    });

    loadContent(default_route);
    $(document).on("click", ".btn-danger", function (e) {
        e.preventDefault();
        if (confirm(default_confirm_message)) {
            linkToContent(this);
        }
    });
    
    $('.alert-place').ajaxError(function (event, request, settings) {
        $('.progress').hide();
        var alert;
        if (request.status >= 500) {
            alert = new alertMessage(default_error_title, default_error_message, 'error').appendTo(this);
        } else {
            alert = new alertMessage(default_error_title, default_error_message).appendTo(this);
        }
        alert.alert();
    });
    var alertMessage = function (header, text, type) {
        var body = $('<div/>').addClass('alert');
        if (typeof type != 'undefined') {
            body.addClass('alert-' + type);
        }
        var close = $('<button/>').html('&times;').attr('type', 'button').attr('data-dismiss', 'alert').addClass('close').appendTo(body);
        $('<strong/>').html(header).appendTo(body);
        $('<span/>').html('&nbsp;').appendTo(body);
        $('<span/>').html(text).appendTo(body);
        return body;
    }
    
    $(document).on('click', '*[data-ajax=true]', function (e) {
        e.preventDefault();
        var button = $(e.target);
        button.ajaxable('request');
        button.attr('disabled', true);
        button.on('done', function (e) {
            button.attr('disabled', false);
        });
    });
    
});

var loadContent = function (path) {
    $('.progress').show();
    $.ajaxSetup({
        cache:false
    });
    $.get(path, $('form.filterable').serialize(), function (html) {
        $('.progress').hide();
        $('#content').html(html);
        $('.ajax_action').tomodal();
    }, 'html');
}

var linkToContent = function (el) {
    var link = $(el).attr('href') ? $(el).attr('href') : $(el).data('href');
    if (!el) return false;
    loadContent(el);
};


function assign_mass_action(route, alert_text, flush)
{
	var checked = [];
	$.each($('.admin_item_checkbox'), function(index, value){
		if (value.checked)
		{
			checked.push(value.value);
		}
	});
	var obj = {
		ids: checked
	}
	$.post(route, $.param(obj), function(data){
		if (flush)
		{
			$.each($('.admin_item_checkbox'), function(index, value){
				value.checked = false;
			});
		}
		console.log(data);
		$('#alerts').append($('#alert_template').clone().show());
	});
}

function checkbox_mass_action(ids)
{
	var obj = $.parseJSON(ids);
	$.each($('.admin_item_checkbox'), function(index, value){
		console.log(value.value.toString());
		if ($.inArray(value.value.toString(), obj) > -1)
		{
			value.checked = true;
		}
	});
}
