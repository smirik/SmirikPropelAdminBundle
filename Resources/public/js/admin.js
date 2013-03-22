$(document).on('ready', function (e) {

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
    $(document).on('click', 'a.ajax_flag', function (e) {
            e.preventDefault();
            /* @todo multiple choices */
            /* @todo refactoring */
            var that = $(this),
                data = that.data(),
                status = (data.status == 1) ? 0 : 1;

            if (that.attr('disabled')) {
                return false;
            }

            that.attr('disabled', true);
            $.post(that.attr('href'), { 'status': status, 'id': data.id }, function (response) {
                that.attr('disabled', false);
                that.data('status', status);
                $.each(data.text, function (e) {
                    if (this.key == status) {
                        that.html(this.text);
                    }
                });

            }, 'json');
        });
});

var loadContent = function (path) {
    $('.progress').show();
    $.ajaxSetup({
        cache: false
    });

    $.get(path, $('form.filterable').serialize(), function (html) {
        $('.progress').hide();
        $('#content').html(html);
        $('.ajax_action').tomodal();
    }, 'html');
}
$(document).on('submit', 'form.filterable', function (e) {
    e.preventDefault();
    loadContent($(this).attr('action'));
});

var linkToContent = function (el) {
    var link = $(el).attr('href') ? $(el).attr('href') : $(el).data('href');
    if (!el) return false;
    loadContent(link);
};


function assign_mass_action(route, alert_text, flush) {
    var checked = [];
    $.each($('.admin_item_checkbox'), function (index, value) {
        if (value.checked) {
            checked.push(value.value);
        }
    });
    var obj = {
        ids: checked
    }
    $.post(route, $.param(obj), function (data) {
        if (flush) {
            $.each($('.admin_item_checkbox'), function (index, value) {
                value.checked = false;
            });
        }
        console.log(data);
        $('#alerts').append($('#alert_template').clone().show());
    });
}

function checkbox_mass_action(ids) {
    var obj = $.parseJSON(ids);
    $.each($('.admin_item_checkbox'), function (index, value) {
        console.log(value.value.toString());
        if ($.inArray(value.value.toString(), obj) > -1) {
            value.checked = true;
        }
    });
}
