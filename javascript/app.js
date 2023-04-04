jQuery(document).ready(function($) {

    $('.SlectBox').SumoSelect();

    $('.w3dev-mjs-from-now').each(function (index, value) { 
      var _date = $(this).text();
      var _fromnow = moment(_date, "DD-MM-YYYY H:m:s").fromNow();
      $(this).text(_fromnow);
    });

    $('table.wp-list-table.users').on('click', '.js-w3dev-banned-history', function(e) {
        e.preventDefault();
        var _user_id = $(this).data('user-id');
        if (_user_id) {
            $.dialog({
                title: 'Banned History',
                theme: 'material',
                content: function () {
                    var self = this;

                    return $.ajax({
                        url: ajaxurl,
                        method: 'post',
                        data : {action: "w3dev_banned_history", user_id: _user_id}
                    }).done(function (response) {
                        
                        //setTimeout(function(){ self.setContent(response); }, 250);
                        //self.setContentAppend('<br>Version: ');
                        //self.setTitle('Device Information');

                    }).fail(function(){
                        self.setContent('Something went wrong.');
                    });
                },
                contentLoaded: function(data, status, xhr){
                    var self = this;
                    if (status == 'success') {
                        self.setContent(data);                           
                    }
                },
                boxWidth: '500px',
                useBootstrap: false,
            });
        }
    });

    $('.w3dev-settings-section h3').on('click', function(e) {
        var _this = $(this);
        _this.find('.w3dev-toggle-content i').toggleClass('fa-caret-down fa-caret-up');
        
        var _parent = _this.parent();
        var _parent_child = _parent.find('.w3dev-content');
        
        if (_parent.hasClass('closed')) {
            _parent.removeClass('closed');
            _parent_child.css('visibility', 'visible');
        } else {
            _parent.addClass('closed');
            _parent_child.css('visibility', 'hidden');
        }
        _parent_child.slideToggle('fast');
    });
    
    var _users_table = $('table.wp-list-table.users');
    if (_users_table.length > 0) {
        var rows = _users_table.find('> tbody > tr');
        $.each( rows, function( index, value ){
            if ($(this).find('span.banned-user.row-highlight').length > 0) {
                $(this).addClass('w3dev-banned-user-row');
            }
        });
 
    }

    $("#w3dev-js-scrollto-section").on('click', 'a', function(e) {
        var _target = $(this).attr('href');

        $(_target).removeClass('closed');
        $(_target).find('.w3dev-content').css('visibility', 'visible').slideDown('fast')
        $(_target).find('.w3dev-toggle-content i').addClass('fa-caret-down').removeClass('fa-caret-up');

        e.preventDefault();
        $('html, body').animate({
            scrollTop: $(_target).offset().top-60
        }, 1000);
    });

    
    $(".w3dev-back-to-top").on('click', function(e) {
        var _target = $(this).attr('href');
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $(_target).offset().top-60
        }, 1000);
    });

    $('.toggle-ban-user').on('click', function(e) {

        var _this           = $(this);
        var _user_ban       = _this.hasClass('active');
        var _user_id        = _this.data('user-id');
        var _custom_email   = _this.data('ban-email');
        var _now            = moment().format("DD-MM-YYYY");
        var _unban_date     = $('#input-w3dev-unban\\ ' + _user_id).val(); // Double slash to allow for space
        var _accessibility  = _this.hasClass('w3dev-accessibility');
        var _user_row       = $('#user-' + _user_id);
        
        _this.parent().parent().addClass('visible');

        var _flatpickr;

        if (typeof _user_ban === 'undefined') { 
            return;
        } else if(!_user_ban) {

            _this.addClass('active');
            if ( _custom_email != 0) {

                var jc = $.confirm({
                    title: '<i class="fa fa-ban"></i> Ban User',
                    content: '<div id="jconfirm-ban-user"><div id="dtp-toggle-content"><p>Using the drop down menu below select the duration of the ban. Then in the textarea below, enter a brief message to send to the user that explains why they\'ve been banned.</p><select id="input-w3dev-ban-duration" class="selectric"><option value="indefinately">Ban indefinately</option><option value="1 day">Ban for 1 day</option><option value="1 week">Ban for 1 week</option><option value="2 weeks">Ban for 2 weeks</option><option value="1 month">Ban for 1 month</option><option value="date picker">Ban using date picker</option></select><textarea name="" >' + php_vars.default_ban_reason + '</textarea></div><div style="display:none;" id="w3dev-dtp-wrapper"><i class="fa fa-calendar" aria-hidden="true"></i><input type="text" id="input-w3dev-unban-date" value=""></div></div> ',
                    useBootstrap: false,
                    boxWidth: '400px',
                    onOpen: function () {

                        $("#input-w3dev-unban-date").flatpickr({ enable: [{ from: new Date().fp_incr(0), to: new Date().fp_incr(7 * 366 * 100) }], });

                        $('select.selectric').selectric();
                        $('#input-w3dev-ban-duration').on('change', function(e) {
                            var _option = $(this).val();
                            if ( _option == 'date picker' ) {
                                $("#w3dev-dtp-wrapper").show();
                                //$('.jconfirm-buttons').find('.w3dev-bb-date-picker').removeClass('hide');
                            } else {
                                $("#w3dev-dtp-wrapper").hide();
                                //$('.jconfirm-buttons').find('.w3dev-bb-date-picker').addClass('hide');
                            }
                        });
                    },
                    buttons: {
                        calendar: {
                            text: '<i class="fa fa-calendar" aria-hidden="true"></i>',
                            btnClass: 'btn-blue pull-left w3dev-bb-date-picker hide datepicker',
                            action: function(){

                                return false;

                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            action: function(){
                                _this.parent().parent().removeClass('visible');
                                _this.removeClass('active'); 
                            }
                        },
                        send: {
                            text: 'Send',
                            btnClass: 'btn-red',
                            action: function(){

                                var _this_btn = this.$$send;
                                _this_btn.prev('button').remove();
                                _this_btn.html('<i class="fa fa-spinner fa-spin fa-fw" aria-hidden="true"></i> SENDING');

                                var _message = $('#jconfirm-ban-user').find('textarea').val();
                                var _ban_duration = $('#input-w3dev-ban-duration').val();

                                if ( _ban_duration == "date picker") _unban_date = $('#input-w3dev-unban-date').val();

                                _this.find('.fa-stack-1x').removeClass('fa-ban').addClass('fa-spinner fa-spin fa-fw');
                                var data = {
                                    'action': 'w3dev_toggle_ban_user',
                                    'user_id'       : _user_id,
                                    'message'       : _message,
                                    'unban_date'    : _unban_date,
                                    'ban_duration'  : _ban_duration
                                };

                                $.post(ajaxurl, data, function(response) {

                                    _this_btn.html('<i class="fa fa-check" aria-hidden="true"></i> SENT');
                                    var _class = (response == 'banned') ? 'active' : '';
                                    _user_row.find('.w3dev-banned-status').attr('data-balloon','Banned: ' + _now).addClass('active row-highlight');

                                    // check for accessibility
                                    // --
                                    if (_accessibility) {
                                        
                                        _this.removeClass('active').addClass(_class).html('UnBan User');
                                        _user_row.addClass('w3dev-banned-user-row');
                                        alert('User has been banned.')

                                    } else {

                                        _this.find('.fa-stack-1x').removeClass('fa-spinner fa-spin fa-fw').addClass('fa-ban');
                                        _this.removeClass('active').addClass(_class);
                                        _user_row.addClass('w3dev-banned-user-row');
                                        _user_row.find('.warn-ban-user,.icon-ban-date-user').addClass('hide');
                                        
                                    }

                                    _this_btn.unbind('click');

                                    setTimeout(function(){ 
                                        _this.parent().parent().removeClass('visible');
                                        _this.addClass('active'); 
                                        jc.close(); 
                                    }, 1250);

                                }); 

                                return false;

                            }
                        }
                    }
                });

            } else {

                // check for accessibility
                // --
                if (!_accessibility) { 
                    _this.find('.fa-stack-1x').removeClass('fa-ban').addClass('fa-spinner fa-spin fa-fw');
                }

                var data = {
                    'action': 'w3dev_toggle_ban_user',
                    'user_id': _user_id,
                    'message': '',
                    'unban_date': _unban_date,
                };

                $.post(ajaxurl, data, function(response) {

                    _this.find('.fa-stack-1x').removeClass('fa-spinner fa-spin fa-fw').addClass('fa-ban');
                    _this.removeClass('active');

                    var _user_row = $('#user-' + _user_id);
                    _user_row.addClass('w3dev-banned-user-row');
                    _user_row.find('.warn-ban-user,.icon-ban-date-user').addClass('hide');
                    _user_row.find('.w3dev-banned-status').attr('data-balloon','Banned: ' + _now).addClass('active row-highlight');

                    // check for accessibility
                    // --
                    if (_accessibility) { 
                        _this.addClass('active').html('UnBan User');
                        alert('User has been banned.');
                    } else {
                        _this.find('.fa-stack-1x').removeClass('fa-spinner fa-spin fa-fw').addClass('fa-ban');
                        _this.addClass('active'); 
                    }

                    _this.parent().parent().removeClass('visible');

                }); 

            }
        
        } else {

            // check for accessibility
            // --
            if (!_accessibility) { 
                _this.find('.fa-stack-1x').removeClass('fa-ban').addClass('fa-spinner fa-spin fa-fw');
            }

            var data = {
                'action'    : 'w3dev_toggle_ban_user',
                'user_id'   : _user_id,
                'message'   : "",
                'unban_date': _unban_date,
            };

            $.post(ajaxurl, data, function(response) {
                _this.find('.fa-stack-1x').removeClass('fa-spinner fa-spin fa-fw').addClass('fa-ban');
                var _class = (response == 'banned') ? 'active' : '';
                _this.removeClass('active').addClass(_class);
                _user_row.removeClass('w3dev-banned-user-row');
                _user_row.find('.warn-ban-user,.icon-ban-date-user').removeClass('hide');
                _user_row.find('.w3dev-banned-status').attr('data-balloon','Reinstated: ' + _now).removeClass('active row-highlight');

                // check for accessibility
                // --
                if (_accessibility) {
                    _this.removeClass('active').html('Ban User');
                    alert('User has been unbanned.');
                } else {
                    _this.find('.fa-stack-1x').removeClass('fa-spinner fa-spin fa-fw').addClass('fa-ban');
                    _this.removeClass('active');
                }

                _this.parent().parent().removeClass('visible');

            });

        }

    });


    $('.warn-ban-user').on('click', function(e) {

        var _this           = $(this);
        var _user_id        = _this.data('user-id');
        var _reason         = _this.data('allow-reason');
        var _accessibility  = _this.hasClass('w3dev-accessibility');

        _this.addClass('active');
        _this.parent().parent().addClass('visible');

        if ( _reason != 0) {

            var jc = $.confirm({
                title: '<i class="fa fa-exclamation-triangle"></i> Warn User',
                content: '<div id="jconfirm-warn-user"><p>Enter a brief message below to send to the user that explains the reason for the warning.</p><textarea name="" >' + php_vars.default_warn_reason + '</textarea></div>',
                useBootstrap: false,
                boxWidth: '400px',
                buttons: {
                    cancel: {
                        text: 'Cancel',
                        action: function(){
                            _this.parent().parent().removeClass('visible');
                            _this.removeClass('active');
                        }
                    },
                    send: {
                        text: 'Send',
                        btnClass: 'btn-orange',
                        action: function(){

                            var _this_btn = this.$$send;
                            _this_btn.prev('button').remove();
                            _this_btn.html('<i class="fa fa-spinner fa-spin fa-fw" aria-hidden="true"></i> SENDING');

                            var _reason = $('#jconfirm-warn-user').find('textarea').val();
                            _this.find('.fa-stack-1x').removeClass('fa-exclamation-triangle').addClass('fa-spinner fa-spin fa-fw');
                            var data = {
                                'action': 'w3dev_warn_ban_user',
                                'user_id': _user_id,
                                'reason': _reason
                            };

                            $.post(ajaxurl, data, function(response) {
                                _this_btn.html('<i class="fa fa-check" aria-hidden="true"></i> SENT');

                                // check for accessibility
                                // --
                                if (!_accessibility) {
                                    _this.find('.fa-stack-1x').removeClass('fa-spinner fa-spin fa-fw').addClass('fa-exclamation-triangle');
                                } else {
                                    alert('Email has been sent. User has been warned.');
                                }
 
                                setTimeout(function(){ 
                                    _this.parent().parent().removeClass('visible');
                                    _this.removeClass('active'); 
                                    jc.close(); 
                                }, 1250);

                            });

                            return false;

                        }
                    }
                }
            });

        } else {

            // check for accessibility
            // --
            if (!_accessibility) {
                _this.find('.fa-stack-1x').removeClass('fa-exclamation-triangle').addClass('fa-spinner fa-spin fa-fw');
            }

            var data = {
                'action': 'w3dev_warn_ban_user',
                'user_id': _user_id,
                'reason': _reason,
            };

            $.post(ajaxurl, data, function(response) {

                // check for accessibility
                // --
                if (!_accessibility) {
                    _this.find('.fa-stack-1x').removeClass('fa-spinner fa-spin fa-fw').addClass('fa-exclamation-triangle');
                } else {
                    alert('Email has been sent. User has been warned.');
                }

                _this.removeClass('active');
                _this.parent().parent().removeClass('visible');

            });

        }

    });

    $('#input-w3dev-ban-user').on('change', function(e) {
        var _this       = $(this);
        if ( _this.is(':checked') && $('#w3dev-ban-checked').val() != 0 ) {
            var message = prompt("What is the reason for banning this user?", "");
            if( !Boolean(message) ) {
                _this.prop('checked', false);
                return; 
            }

            $('#w3dev-ban-reason').val(message);
         } else
            $('#w3dev-ban-reason').val("");

    if( _this.is(':checked') )
            $('#js-w3dev-unban-date').show();
        else
            $('#js-w3dev-unban-date').hide();   

        e.preventDefault();
    });

    $('#input-ban-email-default').on('change', function(e) {
        var _this = $(this);
        if (_this.is(':checked')) {
            $('#js-ban-email-message').hide();
            $('#js-default-ban-reason').show();
        } else {
            $('#js-ban-email-message').show();
            $('#js-default-ban-reason').hide();
        }
        e.preventDefault();
    });
    $('#input-change-status').on('change', function(e) {
        var _this = $(this);
        if (_this.is(':checked')) {
            $('#js-post-status').show();
        } else {
            $('#js-post-status').hide();
            $('#input-post-status').val('');
        }
        e.preventDefault();
    });

    $('#input-on-ban-change-user-role').on('change', function(e) {
        var _this = $(this);
        if (_this.is(':checked')) {
            $('#js-set-banned-user-role').show();
        } else {
            $('#js-set-banned-user-role').hide();
            $('#input-set-banned-user-role').val('');
        }
        e.preventDefault();
    });

    $('#input-on-unban-change-user-role').on('change', function(e) {
        var _this = $(this);
        if (_this.is(':checked')) {
            $('#js-set-unbanned-user-role').show();
        } else {
            $('#js-set-unbanned-user-role').hide();
            $('#input-set-unbanned-user-role').val('');
        }
        e.preventDefault();
    });

    $('#input-enable-support-cpt').on('change', function(e) {
        var _this = $(this);
        if (_this.is(':checked')) {
            $('#js-supported-cpt').show();
        } else {
            $('#js-supported-cpt').hide();
            $('#input-supported-cpt').val('');
        }
        e.preventDefault();
    });

    $('#input-force-logout').on('change', function(e) {
        var _this = $(this);
        if (_this.is(':checked')) {
            $('#js-display-message-extras').hide();
        } else {
            $('#js-display-message-extras').show();
        }
        e.preventDefault();
    });

    $('#input-display-message').on('change', function(e) {
        var _this = $(this);
        if (_this.is(':checked')) {
            $('#js-display-message-extras').show();
        } else {
            $('#js-display-message-extras').hide();
        }
        e.preventDefault();
    });

    $('#input-custom-logout').on('change', function(e) {
        var _this = $(this);
        if (_this.is(':checked')) {
            $('#js-custom-logout-url').show();
        } else {
            $('#js-custom-logout-url').hide();
        }
        e.preventDefault();
    });

    $('#input-warn-user').on('change', function(e) {
        var _this = $(this);
        if (_this.is(':checked')) {
            $('#js-warn-user-reason').show();
            $('#js-default-warn-reason').show();
        } else {
            $('#js-warn-user-reason').hide();
            $('#js-default-warn-reason').hide();
        }
        e.preventDefault();
    });

    $('#input-frontend-notification-force-logout').on('change', function(e) {
        var _this = $(this);
        if (_this.is(':checked')) {
            $('#input-frontend-notification-hide').prop('checked', false).attr("disabled", true);
        } else {
            $('#input-frontend-notification-hide').removeAttr("disabled");
        }
        e.preventDefault();
    });


    $('#w3dev-save-ban-user-settings').on('click', function(e) {

        var _this = $(this);
        _this.html('<i class="fa fa-spinner fa-spin fa-fw" aria-hidden="true"></i> Saving')
        
        // get input data
        // --
        var _change_posts_status                    = $('#input-change-status').is(':checked') ? 1 : 0;
        var _post_status                            = $('#input-post-status').val();
        var _on_ban_change_user_role                = $('#input-on-ban-change-user-role').is(':checked') ? 1 : 0;
        var _set_banned_user_role                   = $('#input-set-banned-user-role').val();
        var _on_unban_change_user_role              = $('#input-on-unban-change-user-role').is(':checked') ? 1 : 0;
        var _set_unbanned_user_role                 = $('#input-set-unbanned-user-role').val();
        var _enable_support_cpt                     = $('#input-enable-support-cpt').is(':checked') ? 1 : 0;
        var _supported_cpt                          = $('#input-supported-cpt').val();
        var _display_message                        = $('#input-display-message').is(':checked') ? 1 : 0;
        var _custom_message                         = $('#input-custom-message').val();
        var _force_logout                           = $('#input-force-logout').is(':checked') ? 1 : 0;
        var _custom_logout                          = $('#input-custom-logout').is(':checked') ? 1 : 0;
        var _custom_logout_url                      = $('#input-custom-logout-url').val();
        var _close_panels                           = $('#input-close-panels').is(':checked') ? 1 : 0;
        var _ban_email                              = $('#input-ban-email').is(':checked') ? 1 : 0;
        var _ban_email_default                      = $('#input-ban-email-default').is(':checked') ? 1 : 0;
        var _ban_email_message                      = $('#input-ban-email-message').val();
        var _hide_banned_users_comments             = $('#input-hide-banned-users-comments').is(':checked') ? 1 : 0;
        var _scramble_banned_users_password         = $('#input-scramble-banned-users-password').is(':checked') ? 1 : 0;
        var _disable_password_reset_banned_users    = $('#input-disable-password-reset-banned-users').is(':checked') ? 1 : 0;
        var _set_spammer_option                     = $('#input-set-spammer-option').is(':checked') ? 1 : 0;
        var _unset_spammer_option                   = $('#input-unset-spammer-option').is(':checked') ? 1 : 0;
        var _default_ban_reason                     = $('#input-default-ban-reason').val();
        var _default_warn_reason                    = $('#input-default-warn-reason').val();

        var _users_tbl_row_highlighted              = $('#input-users-tbl-row-highlighted').is(':checked') ? 1 : 0;
        var _users_tbl_data_column                  = $('#input-users-tbl-data-column').is(':checked') ? 1 : 0;
        var _banned_login_message                   = $('#input-banned-login-message').val();
        var _date_format                            = $('#input-date-format').val();
        var _time_enable                            = $('#input-time-enable').is(':checked') ? 1 : 0;
        var _send_notification_new_post             = $('#input-send-notification-new-post').is(':checked') ? 1 : 0;
        var _notification_emails                    = $('#input-notification-emails').val();
        var _users_tbl_geoip_data_column            = $('#input-users-tbl-geoip-data-column').is(':checked') ? 1 : 0;
        var _capture_login_geoip_data               = $('#input-capture-login-geoip-data').is(':checked') ? 1 : 0;
        var _warn_user                              = $('#input-warn-user').is(':checked') ? 1 : 0;
        var _send_notification_new_post             = $('#input-send-notification-new-post').is(':checked') ? 1 : 0;
        var _notification_emails                    = $('#input-notification-emails').val();
        var _users_tbl_geoip_data_column            = $('#input-users-tbl-geoip-data-column').is(':checked') ? 1 : 0;
        var _warn_user_reason                       = $('#input-warn-user-reason').is(':checked') ? 1 : 0;
        var _frontend_banned_notification           = $('#input-frontend-banned-notification').is(':checked') ? 1 : 0;
        var _frontend_notification_force_logout     = $('#input-frontend-notification-force-logout').is(':checked') ? 1 : 0;
        var _frontend_notification_hide             = $('#input-frontend-notification-hide').is(':checked') ? 1 : 0;
        var _enable_accessibility                   = $('#input-enable-accessibility').is(':checked') ? 1 : 0;
        var _security_enable_admin_override         = $('input[name=input-enable-admin-override]').is(':checked') ? 1 : 0;
        var _security_set_moderator_roles           = $('input[name=input-security-set-moderator-roles]').is(':checked') ? 1 : 0;
        var _security_moderator_roles               = $('select[name=input-security-moderator-roles]').val();
        var _security_set_moderated_roles           = $('input[name=input-security-set-moderated-roles]').is(':checked') ? 1 : 0;
        var _security_moderated_roles               = $('select[name=input-security-moderated-roles]').val();

        // extensions
        // --
        var _ext_ultimate_member                   = $('#input-ext-ultimate-member').is(':checked') ? 1 : 0;
        

        // autoloaders
        // --
        var _autoload_fa                            = $('#input-autoload-fa').is(':checked') ? 1 : 0;
        var _autoload_jq_confirm                    = $('#input-autoload-jq-confirm').is(':checked') ? 1 : 0;
        var _autoload_datatables                    = $('#input-autoload-datatables').is(':checked') ? 1 : 0;
        var _autoload_notify                        = $('#input-autoload-notify').is(':checked') ? 1 : 0;
        var _autoload_selectric                     = $('#input-autoload-selectric').is(':checked') ? 1 : 0;
        var _autoload_flatpickr                     = $('#input-autoload-flatpickr').is(':checked') ? 1 : 0;
        var _autoload_alertify                      = $('#input-autoload-alertify').is(':checked') ? 1 : 0;
        var _autoload_faanimation                   = $('#input-autoload-faanimation').is(':checked') ? 1 : 0;

        var data = {
            'action':                               'w3dev_save_ban_user_settings',
            'change_posts_status':                  _change_posts_status,
            'post_status':                          _post_status,
            'on_ban_change_user_role':              _on_ban_change_user_role,
            'set_banned_user_role':                 _set_banned_user_role,
            'on_unban_change_user_role':            _on_unban_change_user_role,
            'set_unbanned_user_role':               _set_unbanned_user_role,
            'enable_support_cpt':                   _enable_support_cpt,
            'supported_cpt':                        _supported_cpt,
            'display_message':                      _display_message,
            'custom_message':                       _custom_message,
            'force_logout':                         _force_logout,
            'custom_logout':                        _custom_logout,
            'custom_logout_url':                    _custom_logout_url,
            'close_panels':                         _close_panels,
            'unban_date':                           1,
            'ban_email':                            _ban_email,
            'ban_email_default':                    _ban_email_default,
            'ban_email_default_message':            _ban_email_message, 
            'hide_banned_users_comments':           _hide_banned_users_comments,
            'scramble_banned_users_password':       _scramble_banned_users_password,
            'disable_password_reset_banned_users':  _disable_password_reset_banned_users,
            'set_spammer_option':                   _set_spammer_option,
            'unset_spammer_option':                 _unset_spammer_option,
            'default_ban_reason':                   _default_ban_reason, 
            'default_warn_reason':                  _default_warn_reason, 
            'users_tbl_row_highlighted':            _users_tbl_row_highlighted,
            'users_tbl_data_column':                _users_tbl_data_column,
            'banned_login_message':                 _banned_login_message,
            'date_format':                          _date_format,
            'time_enable':                          _time_enable,
            'send_notification_new_post':           _send_notification_new_post,
            'notification_emails':                  _notification_emails,
            'users_tbl_geoip_data_column':          _users_tbl_geoip_data_column,
            'capture_login_geoip_data':             _capture_login_geoip_data,
            'warn_user':                            _warn_user,
            'warn_user_reason':                     _warn_user_reason,
            'frontend_banned_notification':         _frontend_banned_notification,
            'frontend_notification_force_logout':   _frontend_notification_force_logout,
            'frontend_notification_hide':           _frontend_notification_hide,
            'enable_accessibility':                 _enable_accessibility,
            'autoload_fa':                          _autoload_fa,
            'autoload_jq_confirm':                  _autoload_jq_confirm,
            'autoload_datatables':                  _autoload_datatables,
            'autoload_notify':                      _autoload_notify,
            'autoload_selectric':                   _autoload_selectric,
            'autoload_flatpickr':                   _autoload_flatpickr,
            'autoload_alertify':                    _autoload_alertify,
            'autoload_faanimation':                 _autoload_faanimation,
            'ext_ultimate_member':                  _ext_ultimate_member,
            'security_enable_admin_override':       _security_enable_admin_override,
            'security_set_moderator_roles':         _security_set_moderator_roles,
            'security_moderator_roles':             _security_moderator_roles,
            'security_set_moderated_roles':         _security_set_moderated_roles,
            'security_moderated_roles':             _security_moderated_roles

        };

        $.post(ajaxurl, data, function(response) {
            _this.html('Save Settings');
            $('#js-save-message').fadeIn().delay(2500).fadeOut();
        });

        e.preventDefault();

    });

    $('#w3dev-save-ban-email-template').on('click', function(e) {

        var _this = $(this);
        _this.html('<i class="fa fa-spinner fa-spin fa-fw" aria-hidden="true"></i> Saving');

        var _w3dev_ban_subject_title            = $('#input-ban-user-subject-title').val();
        var _w3dev_ban_email_template           = tinyMCE.get('ban_editor').getContent();
        var _w3dev_ban_cc_field                 = $('#input-ban-user-cc-field').val();
        var _w3dev_ban_bcc_field                = $('#input-ban-user-bcc-field').val();

        var _w3dev_unban_subject_title          = $('#input-unban-user-subject-title').val();
        var _w3dev_unban_email_template         = tinyMCE.get('unban_editor').getContent();
        var _w3dev_unban_indefinite_date_tag    = $('#input-unban-indefinite-date-tag').val();
        var _w3dev_unban_cc_field               = $('#input-unban-user-cc-field').val();
        var _w3dev_unban_bcc_field              = $('#input-unban-user-bcc-field').val();

        var _w3dev_warn_subject_title           = $('#input-warn-user-subject-title').val();
        var _w3dev_warn_email_template          = tinyMCE.get('warn_editor').getContent();
        var _w3dev_warn_cc_field                = $('#input-warn-user-cc-field').val();
        var _w3dev_warn_bcc_field               = $('#input-warn-user-bcc-field').val();

        // console.log(_w3dev_unban_email_template);
        // console.log(_w3dev_ban_email_template);

        var data = {
            'action':                       'w3dev_save_ban_email_template',
            'ban_subject_title':            _w3dev_ban_subject_title,
            'ban_body':                     _w3dev_ban_email_template,
            'ban_cc_field':                 _w3dev_ban_cc_field,
            'ban_bcc_field':                _w3dev_ban_bcc_field,

            'unban_subject_title':          _w3dev_unban_subject_title,
            'unban_body':                   _w3dev_unban_email_template,
            'unban_cc_field':               _w3dev_unban_cc_field,
            'unban_bcc_field':              _w3dev_unban_bcc_field,

            'unban_indefinite_date_tag':    _w3dev_unban_indefinite_date_tag,
            'warn_subject_title':           _w3dev_warn_subject_title,
            'warn_body':                    _w3dev_warn_email_template,
            'warn_cc_field':                _w3dev_warn_cc_field,
            'warn_bcc_field':               _w3dev_warn_bcc_field,
        };

        $.post(ajaxurl, data, function(response) {
            _this.html('Save Template');
        }); 
        e.preventDefault();
    });

    $('#w3dev-tabs li a').on('click', function(e) {

        var _this = $(this);
        var _tab = _this.data('tab');

        // hide all tabs
        // --
        $('.w3dev-tab').hide();

        $('#w3dev-tabs').find('li a').removeClass('active');
        _this.addClass('active');

        // show the selected tab
        // --
        $('#tab-'+_tab).show();

    });


    $(".datepicker" ).flatpickr({
        enable: [{
                from: new Date().fp_incr(0),
                to: new Date().fp_incr(7 * 366 * 100) // 7 days from now
        }],     
        onOpen: function(dateObj, dateStr, instance){

        },
        onClose: function(dateObj, dateStr, instance){
            $('.row-actions').removeClass('sticky-actions');
        }
    });

    $('#input-w3dev-ban-user').on('change', function(e) {
        var _this = $(this);
        
        if ( _this.is(':checked') ) {
            $('#js-w3dev-unban-date').show();
        } else {
            $('#js-w3dev-unban-date').hide();      
        }
    });
    
    $('.datepicker').on('click', function(e) {
        $(this).parent().parent().addClass('sticky-actions');
    });

});

function w3dev_datepicker(ID) { 
    document.getElementById("input-w3dev-unban-" + ID).focus(); 
}
