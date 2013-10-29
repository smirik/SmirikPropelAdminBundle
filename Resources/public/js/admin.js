(function($){
    var $document = $(document),
        $progress =  $('.progress');
    ;

    function stashFilter(path)
    {
        var elem = document.createElement('a')
            , query = []
            , $form = $('form.filterable')
            , temp
            ;

        if ("undefined" == typeof localStorage) {
            return;
        }

        elem.href = path;
        if (elem.search.length) {
            query.push(elem.search.substr(1));
        }

        temp = $form.serialize();
        if (temp.length) {
            query.push(temp);
        }

        if (query.length) {
            localStorage.setItem(window.location.pathname, query.join("&"));
        }
    }

    function unstashFilter()
    {
        if ("undefined" == typeof localStorage) {
            return;
        }

        return localStorage.getItem(window.location.pathname);
    }

    function linkToContent(el)
    {
        var link = $(el).attr('href') ? $(el).attr('href') : $(el).data('href');
        if (!el) return;
        loadContent(link);
    }

    function loadContent(path, query)
    {
        var $form = $('form.filterable');

        $progress.show();

        if ("undefined" == typeof query) {
            query = $form.serialize();

            stashFilter(path);
        }

        $.get(path, query)
            .done(function(html){
                $('#content')
                    .html(html)
                    .trigger($.Event('propel-admin-content-load'))
                ;

                $('.ajax_action').tomodal();
            })
            .always(function(){
                $progress.hide();
            })
            .fail(function(xhr, textStatus){
                console.error(textStatus)
            })
        ;
    }

    function AlertMessage(header, text, type)
    {
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

    $(function(){
        $document.on("click.propelAdmin", ".pagination a", function (e) {
            e.preventDefault();
            linkToContent(this);
        });
        $document.on("click.propelAdmin", ".btn-danger", function (e) {
            e.preventDefault();
            if (confirm(typeof default_confirm_message !== "undefined" ? default_confirm_message : "Are you sure?")) {
                linkToContent(this);
            }
        });
        $document.on('click.propelAdmin', 'a.chain', function (e) {
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
            $.post(that.attr('href'), { 'status': status, 'id': data.id }, function () {
                that.attr('disabled', false);
                that.data('status', status);
                $.each(data.text, function () {
                    if (this.key == status) {
                        that.html(this.text);
                    }
                });

            }, 'json');

            return true;
        });
        $document.on('click.propelAdmin', 'a.ajaxable', function (e) {
            e.preventDefault();
            var button = $(e.target);
            if (!button.attr('data-url'))
            {
                button = button.parent();
            }
            button.ajaxable('request');
            button.attr('disabled', true);
            button.on('done', function () {
                button.attr('disabled', false);
            });
        });
        $document.on('click.propelAdmin', 'input#select-all-checkbox', function(){
            var select_all_checkbox = $('input:checkbox#select-all-checkbox');
            if (select_all_checkbox.prop('checked'))
            {
                $('input.admin_item_checkbox').prop('checked', "checked");
            } else
            {
                $('input.admin_item_checkbox').prop('checked', false);
            }
        });
        $document.on("click.propelAdmin", ".sortable", function (e) {
            e.preventDefault();
            linkToContent(this);
        });
        $document.on('submit.propelAdmin', 'form.filterable', function (e) {
            e.preventDefault();
            loadContent($(this).attr('action'));
        });

        $.ajaxSetup({
            cache: false
        });

        $('.alert-place').ajaxError(function (event, request) {
            $('.progress').hide();
            var alert;
            if (request.status >= 500) {
                alert = new AlertMessage(default_error_title, default_error_message, 'error').appendTo(this);
            } else {
                alert = new AlertMessage(default_error_title, default_error_message).appendTo(this);
            }
            alert.alert();
        });

        if ("undefined" !== typeof default_route){
            loadContent(
                default_route,
                unstashFilter()
            );
        }
    });
})(jQuery);


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