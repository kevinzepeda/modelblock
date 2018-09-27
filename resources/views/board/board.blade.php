@extends('app')

@section('content')
<body class="skin-blue sidebar-collapse" style="background-color: #ecf0f5 !important;">
<div class="se-pre-con"></div>
<div id="main-content-wraper">

    @include('menu')
    @include('board.board-menu')

    @if(is_object($board))
        @include('board.board-kanban')
        @include('board.board-map')
        @include('board.board-widgets')
    @else
        @include('board.board-clean')
    @endif

    </div>

    <a class="board-map" href="#">
        <div id="kougiland">
            <div class="open-map">
                <i class="ion-arrow-expand">&nbsp;</i>&nbsp;
                {{trans('board.board_map')}}
            </div>
            <div class="close-map">
                <i class="ion-arrow-shrink">&nbsp;</i>&nbsp;
                {{trans('board.close_map')}}
            </div>

        </div>
    </a>

    <div class="board-zoom">
        <div id="kougiland">
            <i class="fa fa-search-plus" style="padding-right:20px;cursor: pointer;" id="zoom-in">&nbsp;</i>
            <div style="width:1px;border-right: 1px dotted white;display:inline;"></div>
            <i class="fa fa-search-minus" style="padding-left:21px;cursor: pointer;" id="zoom-out">&nbsp;</i>
        </div>
    </div>

    <!-- jQuery 2.1.3 -->
    <script src="{{ asset('/assets/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
    <!-- Bootstrap 3.3.2 JS -->

    <script src="{{ asset('/assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/assets/plugins/bootstrap-growl/growl.js') }}" type="text/javascript"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('/assets/plugins/daterangepicker/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/assets/plugins/daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>

    <!-- InputMask -->
    <script src="{{ asset('/assets/plugins/input-mask/jquery.inputmask.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/assets/plugins/input-mask/jquery.inputmask.extensions.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/assets/plugins/input-mask/jquery.inputmask.regex.extensions.js') }}" type="text/javascript"></script>

    <script src="{{ asset('/assets/dist/js/sprymap.js') }}" type="text/javascript"></script>

    <!-- block ui -->
    <script src="{{ asset('/assets/plugins/jquery-blockui/jquery.blockUI.js') }}" type="text/javascript"></script>

    <!-- Gridster -->
    <script src="{{ asset('/assets/plugins/gridster/jquery.gridster.js') }}?{{md5(date("Y-m-d h:i:s"))}}" type="text/javascript"></script>

    <script src="{{ asset('/assets/plugins/summernote/summernote.min.js') }}" type="text/javascript"></script>

        <!-- FastClick -->
    <script src='{{ asset('/assets/plugins/fastclick/fastclick.min.js') }}'></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/assets/dist/js/app.min.js') }}" type="text/javascript"></script>

    <script src='{{ asset('/assets/plugins/sticky/sticky.js') }}' type="text/javascript"></script>

    <script src="{{ asset('/assets/plugins/select2/select2.js') }}" type="text/javascript"></script>

    <script src="{{ asset('/assets/plugins/qtip/jquery.qtip.js') }}" type="text/javascript"></script>

    <script src="{{ asset('/assets/plugins/jQueryUI/jquery.ui.js') }}" type="text/javascript"></script>

    <script src="{{ asset('/assets/plugins/foggy/foggy.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        var gridster;


        $(function(){

            var zoomLevel = 0.6;

            $("#zoom-out").click(function(){
                if(zoomLevel - 0.1 > 0.5) {
                    zoomLevel = zoomLevel - 0.1;
                    $('.bmapzoom').animate({'zoom': zoomLevel}, 'fast');
                }
            });

            $("#zoom-in").click(function(){
                if(zoomLevel + 0.1 < 1.0) {
                    zoomLevel = zoomLevel + 0.1;
                    $('.bmapzoom').animate({'zoom': zoomLevel}, 'fast');
                }
            });

            $('.board').kinetic();

            $(".board-map").click(function(){
                zoomLevel = 0.6;
                $('.bmapzoom').animate({'zoom': zoomLevel}, 0);
                if ($("#board-map").css("display") == "none") {
                    $(".open-map").hide();
                    $(".board-zoom").fadeIn();
                    $(".close-map").show();
                    $("body").css('overflow', 'hidden');
                    $("#board-map").show();
                } else {
                    $(".close-map").hide();
                    $("#board-map").hide();
                    $(".board-zoom").fadeOut();
                    $("body").css('overflow', 'visible');
                    $(".open-map").show();
                    $(".toolbar-menu").show();

                }
            });

            <?php
                $columns_number = 0;
                $parent_board = 'false';
                $child_board = 'false';
                if(is_object($board)){
                    $columns = json_decode($board->columns);
                    $columns_number = count($columns);
                    if(is_object($board->parent_board)){
                        $columns_number++;
                        $parent_board = $board->parent_board->public_hash;
                    }
                    if(is_object($board->child_board)){
                        $columns_number++;
                        $child_board = $board->child_board->public_hash;;
                    }
                }else{
                     $columns_number = 0;
                }

                $y_transition = 0;
                foreach($bards_map as $key => $boardz) {

                    $board_col_num = json_decode($boardz->columns);

                    $map_col_number = count($board_col_num);
                    if(!empty($boardz->parent_board)){
                        $map_col_number++;
                    }
                    if(!empty($boardz->child_board)){
                        $map_col_number++;
                    }
                    $y_transition  = $map_col_number + $y_transition;
                }


            ?>

            @if(is_object($board))
            var parent_board = {{(is_object($board->parent_board))?1:0}};
            var child_board = {{(is_object($board->child_board))?1:0}};
            @endif

            var parent_exists = '{{$parent_board}}';
            var child_exists = '{{$child_board}}';

            var task_columns = {{$columns_number}};

            var map_columns = {{$y_transition}};

            //$("#boardc").sticky({topSpacing:0});

//            if(task_columns < 7){
//                var task_width = 250;
//            }else{
            var task_width = 250;
//            }

            $(".kanban-coulmn").css('width', task_width);

            @if(!Auth::user()->isClient())

            $(".column-edit").click(function(){
                $(this).unbind();
                $(this).find('.col-name').hide();
                $(this).find('.col-edit').hide();
                $(this).find('.col-edit-input').show();
                $(this).find('.col-edit-input').focus();
            });

            $(".col-edit-input").keyup(function( event ){
                if(event.which == 13 && event.ctrlKey == true || event.which == 13){
                    $(this).hide();
                    $(this).parent().find('.col-edit').hide();
                    $(this).parent().find('.col-name').show();
                    $(this).qtip("hide");
                    $("#c_"+$(this).attr("id")).html($(this).val().toUpperCase());

                    $(this).parent().bind({
                      mouseenter: function(e) {
                        $(this).fadeTo('fast',0.8,function(){});
                        $(this).find('.col-name').hide();
                        $(this).find('.col-edit').show();
                      },
                      mouseleave: function(e) {
                        $(this).fadeTo('fast',1,function(){});
                        $(this).find('.col-edit').hide();
                        $(this).find('.col-name').show();
                      }
                    });

                    $(this).parent().bind('click', function(){
                         $(this).unbind();
                        $(this).find('.col-name').hide();
                        $(this).find('.col-edit').hide();
                        $(this).find('.col-edit-input').show();
                        $(this).find('.col-edit-input').focus();
                    });

                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'column_rename',
                        "hash": $("#boards-list").val(),
                        "column": $(this).val(),
                        "position": $(this).attr('id')
                    };

                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                //console.log(data);
                                if(data.status == 'ok' && data.result > 0){
                                    ktNotification('{{trans('board.notification.colum_renamed')}}', '', 2000, true)
                                }
                            },
                            'json'
                    );
                }
            });

            $(".column-edit").hover(function(){
                    $(this).fadeTo('fast',0.8,function(){});
                    $(this).find('.col-name').hide();
                    $(this).find('.col-edit').show();
                },function(){
                    $(this).fadeTo('fast',1,function(){});                    
                    $(this).find('.col-edit').hide();
                    $(this).find('.col-name').show();
            });
            @endif
            var gridster = $(".gridster-kanban > ul").gridster({
                widget_margins: [13,7],
                widget_base_dimensions: [task_width, 150],
                min_cols: task_columns,
                max_cols: task_columns,
                animate: true,
                @if((@$board->lock == 0  || Auth::user()->isAdmin() || Auth::user()->canManage()) && !Auth::user()->isClient())
                draggable: {
                    stop: function(event, ui){
                        var affected_tasks = this.serialize_changed();
                        var update_positions = [];

                        $.each( affected_tasks, function( key, value ) {

                            if (child_exists != 'false' && value.col != task_columns && value.col != 1
                                || parent_exists != 'false' && value.col != 1 && value.col != task_columns) {
                                $("#" + value.id).removeClass('ignore-update');
                                $("#" + value.id).find('.rc').fadeOut();
                                $("#" + value.id).find('.rp').fadeOut();
                            }

                            if($("#" + value.id).hasClass('ignore-update') == false) {
                                $("#" + value.id).find('.rc').fadeOut();
                                $("#" + value.id).find('.rp').fadeOut();
                                if (parent_exists != 'false' && value.col == 1) {
                                    var post_data = {
                                        "_token": '{{ csrf_token() }}',
                                        "event": 'completed_task',
                                        "task_id": value.id,
                                        "board_hash": parent_exists,
                                        "completed": 0
                                    };
                                    $.post(
                                            '{{ url('/api') }}',
                                            post_data,
                                            function (data) {
                                                if (data.status == 'ok') {
                                                    ktNotification('{{trans('board.notification.previous_stage')}}', '', 500, true)
                                                    $("#" + value.id).addClass('ignore-update');
                                                    $("#" + value.id).find('.rc').fadeIn();
                                                }
                                            },
                                            'json'
                                    );
                                }

                                if (child_exists != 'false' && value.col == task_columns) {
                                    var post_data = {
                                        "_token": '{{ csrf_token() }}',
                                        "event": 'completed_task',
                                        "task_id": value.id,
                                        "board_hash": child_exists,
                                        "completed": 1
                                    };

                                    $.post(
                                            '{{ url('/api') }}',
                                            post_data,
                                            function (data) {
                                                if (data.status == 'ok') {
                                                    ktNotification('{{trans('board.notification.task_moved_next_stage')}}', '', 500, true)
                                                    $("#" + value.id).addClass('ignore-update');
                                                    $("#" + value.id).find('.rp').fadeIn();
                                                }
                                            },
                                            'json'
                                    );
                                }
                                update_positions.push({
                                    'task': {
                                        'id': value.id,
                                        'col': value.col,
                                        'row': value.row
                                    }
                                });
                            }
                        });

                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'state_update',
                            "hash": $("#boards-list").val(),
                            "ids": update_positions
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function (data) {
                                    if (data.status == 'error') {
                                        var messages = '';
                                        var count = 1;
                                        $.notify({
                                            icon: '{{asset('/assets/error.png')}}',
                                            title: '{{trans('board.notification.failure')}}',
                                            message: data.message
                                        }, {
                                            placement: {
                                                from: "bottom",
                                                align: "right"
                                            },
                                            type: 'minimalist',
                                            delay: 3000,
                                            newest_on_top: true,
                                            allow_dismiss: true,
                                            z_index: 99999999999,
                                            animate: {
                                                enter: 'animated fadeInUp',
                                                exit: 'animated fadeOutUp'
                                            },
                                            icon_type: 'image',
                                            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                            '<img data-notify="icon" class="img-circle pull-left">' +
                                            '<span data-notify="title">{1}</span>' +
                                            '<span data-notify="message">{2}</span>' +
                                            '</div>'
                                        });
                                    } else if (data.status == 'ok') {
                                        //Nothing should happen...
                                    }
                                },
                                'json'
                        );
                    }
                },
                @endif
                resize: {
                    enabled: false
                },
                serialize_params: function($w, wgd) {
                    return { id: $w.prop('id'), col: wgd.col, row: wgd.row, size_x: wgd.size_x, size_y: wgd.size_y }
                },
                avoid_overlapped_widgets: true,
                autogenerate_stylesheet: true
            }).data('gridster');

            var gridster_map = $(".gridster-map > ul").gridster({
                widget_margins: [13,7],
                widget_base_dimensions: [task_width, 150],
                min_cols: map_columns,
                max_cols: map_columns,
                animate: true,
                resize: {
                    enabled: false
                },
                serialize_params: function($w, wgd) {
                    return { id: $w.prop('id'), col: wgd.col, row: wgd.row, size_x: wgd.size_x, size_y: wgd.size_y }
                },
                avoid_overlapped_widgets: true,
                autogenerate_stylesheet: true
            }).data('gridster');
            @if(is_object($board))
            gridster_map.disable();
            @endif
            $(".go-to-board").click(function(){
               var id = $(this).attr('id');
                window.location.href = "{{url('/board') }}" + '/' + id;
            });

            @if(!Auth::user()->isClient())
            $("#subject_preview").keyup(function( event ){
                if(event.which == 13 && event.ctrlKey == true){
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'subject_update',
                        "hash": $("#boards-list").val(),
                        "task_id": $("#task_id_h").val(),
                        "subject": $(this).val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                if(data.status == 'ok'){
                                    ktNotification('{{trans('board.notification.subject_saved')}}', '', 2000, true)
                                }
                            },
                            'json'
                    );
                }
            });

            //AVOID UPDATING SUBJECT ON FOCUS OUT...
            // $("#subject_preview").focusout(function( event ){
            //     var post_data = {
            //         "_token": '{{ csrf_token() }}',
            //         "event": 'subject_update',
            //         "hash": $("#boards-list").val(),
            //         "task_id": $("#task_id_h").val(),
            //         "subject": $(this).val()
            //     };
            //     $.post(
            //             '{{ url('/api') }}',
            //             post_data,
            //             function( data ) {
            //                 if(data.status == 'ok' && data.result > 0){
            //                     ktNotification('Subject Saved', '', 2000, true)
            //                 }
            //             },
            //             'json'
            //     );
            // });

            $("#estimate_input_task_change").keyup(function( event ){
                if(event.which == 13 && event.ctrlKey == true){
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'estimate_update',
                        "hash": $("#boards-list").val(),
                        "task_id": $("#task_id_h").val(),
                        "estimate": $(this).val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                if(data.status == 'ok' && data.result > 0) {
                                    ktNotification('{{trans('board.notification.estimate_updated')}}', '', 2000, true)
                                    $(".estimate_preview").html(data.new_estimate);
                                    $("#" + $("#task_id_h").val() + " #t_estimate").html(data.new_estimate);
                                    $("#estimate_change").hide();
                                    $(".estimate_preview").show();
                                }else if(data.status == 'ok'){
                                }else{
                                    ktNotification('{{trans('board.notification.estimate_invalid')}}', '', 2000, false)
                                    $("#estimate_change").hide();
                                    $("#estimate_input_task_change").val('');
                                    $(".estimate_preview").show();
                                }
                            },
                            'json'
                    );
                }
            });

            $("#comment").keydown(function( event ) {
                if (event.which == 13 && event.ctrlKey == true) {
                    event.preventDefault();
                    return false;
                }
            });

            $("#comment").keyup(function( event ){
                if(event.which == 13 && event.ctrlKey == true){
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'comment',
                        "task_id": $("#task_id_h").val(),
                        "comment": $(this).val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                if(data.status == 'ok') {
                                    ktNotification('{{trans('board.notification.comment_posted')}}', '', 2000, true)
                                    $("#comment").val('');
                                    $("#work_stream").prepend('<div id="comment_'+data.response.id+'" class="row" style="margin: 0px !important;font-size:13px;border-bottom: 1px solid #DBDBDB;padding:20px;text-align:justify;"> <div class="col-md-12" > <div class="box-tools pull-left" style="padding-right:20px;"> <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image='+ data.response.avatar +'" class="user-image-task" alt="User Image"></div><div><strong>' + data.response.author + '.</strong>: '+ data.response.comment + '</div><span class="direct-chat-timestamp pull-right" style="padding-left:10px;"><a href="#" class="delwscomment" style="color:gray;" title="{{trans('board.comment_delete')}}" id="'+data.response.id+'"><i class="ion-trash-a"></i></a></span><span class="direct-chat-timestamp pull-right">'+data.response.date+'</span></div></div>');
                                }else{
                                    ktNotification('{{trans('board.notification.comment_failed')}}', '', 2000, false)
                                }
                            },
                            'json'
                    );
                }
            });

            $("#post_comment").click(function( event ){
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'comment',
                    "task_id": $("#task_id_h").val(),
                    "comment": $("#comment").val()
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'ok') {
                                ktNotification('{{trans('board.notification.comment_posted')}}', '', 2000, true)
                                $("#comment").val('');
                                $("#work_stream").prepend('<div id="comment_'+data.response.id+'" class="row" style="margin: 0px !important;font-size:13px;border-bottom: 1px solid #DBDBDB;padding:20px;text-align:justify;"> <div class="col-md-12" > <div class="box-tools pull-left" style="padding-right:20px;"> <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image='+ data.response.avatar +'" class="user-image-task" alt="User Image"></div><div><strong>' + data.response.author + '.</strong>: '+ data.response.comment + '</div><span class="direct-chat-timestamp pull-right" style="padding-left:10px;"><a href="#" class="delwscomment" style="color:gray;" title="{{trans('board.comment_delete')}}" id="'+data.response.id+'"><i class="ion-trash-a"></i></a></span><span class="direct-chat-timestamp pull-right">'+data.response.date+'</span></div></div>');
                            }else{
                                ktNotification('{{trans('board.notification.comment_failed')}}', '', 2000, false)
                            }
                        },
                        'json'
                );
            });

            $("#estimate_input_task_change").focusout(function( event ){
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'estimate_update',
                        "hash": $("#boards-list").val(),
                        "task_id": $("#task_id_h").val(),
                        "estimate": $(this).val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                if(data.status == 'ok' && data.result > 0) {
                                    ktNotification('{{trans('board.notification.estimate_updated')}}', '', 2000, true)
                                    $(".estimate_preview").html(data.new_estimate);
                                    $("#" + $("#task_id_h").val() + " #t_estimate").html(data.new_estimate);
                                    $("#estimate_change").hide();
                                    $(".estimate_preview").show();
                                }else if(data.status == 'ok'){
                                }else{
                                    ktNotification('{{trans('board.notification.estimate_invalid')}}', '', 2000, false)
                                    $("#estimate_change").hide();
                                    $("#estimate_input_task_change").val('');
                                    $(".estimate_preview").show();
                                }
                            },
                            'json'
                    );
            });

            $("#type_edit").change(function( event ){
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'type_update',
                    "hash": $("#boards-list").val(),
                    "task_id": $("#task_id_h").val(),
                    "type": $("#type_edit").val()
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'ok'){
                                ktNotification('{{trans('board.notification.type_updated')}}', '', 500, true)
                                $("#"+$("#task_id_h").val()+" #t_type").html('{{trans('board.task')}} ' + data.new_type);
                                $(".type_edit").html(data.new_type);
                                $("#type_change").hide();
                                $(".type_edit").show();
                            }
                        },
                        'json'
                );
            });

            $("#project_edit").change(function( event ){
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'project_update',
                    "hash": $("#boards-list").val(),
                    "task_id": $("#task_id_h").val(),
                    "project_id": $("#project_edit").val()
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            //console.log(data);
                            if(data.status == 'ok'){
                                ktNotification('{{trans('board.notification.project_updated')}}', '', 500, true)
                                $("#"+$("#task_id_h").val()+" #t_project").html('{{trans('board.project')}} ' + data.new_type);
                                $(".project_edit").html($("#project_edit :selected").text());
                                $("#project_change").hide();
                                $(".project_edit").show();
                            }
                        },
                        'json'
                );
            });

            $("#board_edit").change(function( event ){
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'board_update',
                    "hash": $("#boards-list").val(),
                    "task_id": $("#task_id_h").val(),
                    "board_hash": $("#board_edit").val()
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'ok'){
                                ktNotification('{{trans('board.notification.task_moved')}}', '', 500, true)
                                $("#board_change").hide();
                                $(".board_change").show();
                                gridster.remove_widget($('#'+ $("#task_id_h")));
                            }
                        },
                        'json'
                );
            });

            $("#delete-board").click(function( event ){
                var cnf = confirm("{{trans('board.warning.board_delete')}}");
                if (cnf == true) {
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'delete_board',
                        "hash": $("#boards-list").val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                if(data.status == 1){
                                    window.location.href = "{{url('/board') }}";
                                }
                            },
                            'json'
                    );
                }
            });

            $("#priority_edit").change(function( event ){
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'priority_update',
                    "hash": $("#boards-list").val(),
                    "task_id": $("#task_id_h").val(),
                    "priority": $("#priority_edit").val()
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'ok'){
                                var priority_colors = {
                                        800 :'box-info',
                                        700 : 'box-success',
                                        600 : 'box-warning',
                                        500 : 'box-danger'
                                };
                                $.each(priority_colors, function(key, css ) {
                                    $('#p_'+$("#task_id_h").val()).removeClass(css);
                                });
                                $('#p_'+$("#task_id_h").val()).addClass(priority_colors[$("#priority_edit").val()]);
                                ktNotification('{{trans('board.notification.priority_updated')}}', '', 500, true)
                                $("#"+$("#task_id_h").val()+" #t_priority").html(data.new_priority);
                                $(".priority_edit").html(data.new_priority);
                                $("#priority_change").hide();
                                $(".priority_edit").show();
                            }
                        },
                        'json'
                );
            });

            $("#manager_edit").change(function( event ){
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'manager_update',
                    "hash": $("#boards-list").val(),
                    "task_id": $("#task_id_h").val(),
                    "manager_id": $("#manager_edit").val()
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'ok'){
                                ktNotification('{{trans('board.notification.manager_updated')}}', '', 500, true)
                                $(".manager_edit").html($("#manager_edit option:selected").text());
                                $("#manager_change").hide();
                                $(".manager_edit").show();
                            }
                        },
                        'json'
                );
            });

            $("#user_edit").change(function( event ){
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'assignee_update',
                    "hash": $("#boards-list").val(),
                    "task_id": $("#task_id_h").val(),
                    "user_id": $("#user_edit").val()
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'ok'){
                                ktNotification('{{trans('board.notification.assignee_updated')}}', '', 500, true)
                                $(".user_edit").html($("#user_edit option:selected").text());
                                $("#"+$("#task_id_h").val()+" #t_assignee").html($("#user_edit option:selected").text().toUpperCase());
                                $("#assignee_change").hide();
                                $(".user_edit").show();
                            }
                        },
                        'json'
                );
            });
            @endif
            @if((@$board->lock == 1 || Auth::user()->isClient()) && count($boards_list) > 0)
                gridster.disable();
            @endif

            if(task_columns < 7) {
                $("#boardc").css('width', '100%');
                $(".gridster").css('padding-top', '60px');
            }else{
                $(".gridster").css('padding-top', '60px');
                $("#boardc").css('width', gridster.container_width + 'px');
            }
            @if(isset($board) && !Auth::user()->isClient())

            $('#edit-board-department').select2({
                data:[
                    {id:0,text:'<div style="width:100%;font-size:12px;text-align:center;">{{trans('board.no_department')}}</div>', value:'custom_col'},@foreach($departments as $department){id:{{$department->id}},text:'<div>{{strtoupper($department->name)}}</div>', value:'{{$department->id}}'}, @endforeach
                ],
                dropdownCssClass: "bigdrop",
                placeholder: "{{trans('board.department_select')}}",
                allowClear: false,
                escapeMarkup: function (m) { return m; }
            });

            $('#new-child-board-department').select2({
                data:[
                    {id:0,text:'<div style="width:100%;font-size:12px;text-align:center;">{{trans('board.no_department')}}</div>', value:'custom_col'},@foreach($departments as $department){id:{{$department->id}},text:'<div>{{strtoupper($department->name)}}</div>', value:'{{$department->id}}'}, @endforeach
                ],
                dropdownCssClass: "bigdrop",
                placeholder: "{{trans('board.department_select')}}",
                allowClear: false,
                escapeMarkup: function (m) { return m; }
            });

            @if(is_object($board->parent_board))
            $(".go-to-parent").click(function(){
                        window.location.href = '{{url('/board') }}' + '/' + '{{$board->parent_board->public_hash}}';
                    });
            @endif

            @if(is_object($board->child_board))
            $(".go-to-child").click(function(){
                window.location.href = '{{url('/board') }}' + '/' + '{{$board->child_board->public_hash}}';
            });
            @endif

            $( "#new-note" ).dialog({
                "open": function() {
                    if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                        $('#main-content-wraper').foggy({
                            blurRadius: 8,          // In pixels.
                            opacity: 1,           // Falls back to a filter for IE.
                            cssFilterSupport: true  // Use "-webkit-filter" where available.
                        });
                    }else{
                        if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                            $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                        }
                    }
                },
                dialogClass: "no-close",
                autoOpen: false,
                modal: true,
                width: 800,
                resizable: false,
                dialogClass: 'main-dialog-class',
                hide: { effect: "fadein", duration: 1000 },
                buttons: [
                    {
                        text: "{{trans('board.widget_general.close')}}",
                        click: function() {
                            $('#main-content-wraper').foggy(false);
                            $( this ).dialog( "close" ).position();;
                        }
                    },
                    {
                        text: "{{trans('board.widget_general.create')}}",
                        style: 'padding-right:0px;',
                        click: function() {
                            $.notifyClose();
                            var post_data = {
                                "_token": '{{ csrf_token() }}',
                                "event": 'new_task',
                                "hash": '{{$board->public_hash}}',
                                "subject": $("#task_subject").val(),
                                "sizey": $("."+$("#board_hash option:selected").val() +" " + "option:selected").val(),
                                "estimate": $("#estimate_input_n_task").val(),
                                "description": $("#task_description").val(),
                                "priority": $("#task_priority option:selected").val(),
                                "type":     $("#task_type option:selected").val(),
                                "user_id":  $("#task_assignee option:selected").val(),
                                "board_id":  $("#board_hash option:selected").val(),
                                "project_id":  $("#project_id option:selected").val()
                            };

                            $.post(
                                    '{{ url('/api') }}',
                                    post_data,
                                    function( data ) {
                                        if(data.status == 'error'){
                                            var messages = '';
                                            var count = 1;
                                            $.each(data.message, function( index, message ) {
                                                messages = messages + (count++) + '.' + message + '<br/>';
                                            });
                                            $.notify({
                                                icon: '{{asset('/assets/error.png')}}',
                                                title: '{{trans('board.notification.failure')}}',
                                                message: messages
                                            },{
                                                placement: {
                                                    from: "bottom",
                                                    align: "right"
                                                },
                                                type: 'minimalist',
                                                delay: 30000,
                                                newest_on_top: true,
                                                allow_dismiss: true,
                                                z_index: 99999999999,
                                                animate: {
                                                    enter: 'animated fadeInUp',
                                                    exit: 'animated fadeOutUp'
                                                },
                                                icon_type: 'image',
                                                template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                                '<img data-notify="icon" class="img-circle pull-left">' +
                                                '<span data-notify="title">{1}</span>' +
                                                '<span data-notify="message">{2}</span>' +
                                                '</div>'
                                            });
                                        }else if(data.status == 'ok'){
                                            window.location.href = "{{url('/board') }}" + '/' + data.response.board.id;
                                            //$('#main-content-wraper').foggy(false);
                                            //$( "#new-kanban" ).dialog( "close" ).position();
                                        }
                                    },
                                    'json'
                            );
                        }
                    }
                ],
                close: function(){task_description
                    if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                        $('#main-content-wraper').foggy(false);
                    }else{
                        if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                            $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                        }
                    }
                }
            });

            $(document).on('click', ".delwscomment", function(){
                var id = $(this).attr('id');
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'delete_comment',
                    "comment_id": id
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'ok'){
                                $("#comment_" + id).remove();
                            }else{
                                ktNotification('{{trans('board.notification.ups')}}', '{{trans('board.notification.admin_contact')}}', 2000, true)
                            }
                        },
                        'json'
                );
            });
            @endif

            $( "#new-kanban" ).dialog({
                "open": function() {
                    if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                        $('#main-content-wraper').foggy({
                            blurRadius: 8,          // In pixels.
                            opacity: 1,           // Falls back to a filter for IE.
                            cssFilterSupport: true  // Use "-webkit-filter" where available.
                        });
                    }else{
                        if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                            $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                        }
                    }
                },
                dialogClass: "no-close",
                autoOpen: false,
                modal: true,
                width: 400,
                resizable: false,
                dialogClass: 'main-dialog-class',
                hide: { effect: "fadein", duration: 1000 },
                buttons: [
                    {
                        text: "{{trans('board.widget_general.close')}}",
                        click: function() {
                            $('#main-content-wraper').foggy(false);
                            $( this ).dialog( "close" ).position();;
                        }
                    },
                    {
                        text: "{{trans('board.widget_general.create')}}",
                        style: 'padding-right:0px;',
                        click: function() {
                            $.notifyClose();
                            var post_data = {
                                "_token": '{{ csrf_token() }}',
                                "event": 'new_board',
                                "name": $("#new-board-name").val(),
                                "description": $("#new-board-desc").val(),
                                "template": $("#new-board-template").val(),
                                "date": $("#new-board-date").val()
                            };

                            if($("#new-board-col").val() != null || $("#new-board-template").val() == 0){
                                post_data.columns = $("#new-board-col").val();
                            }

                            $.post(
                                    '{{ url('/api') }}',
                                    post_data,
                                    function( data ) {
                                        if(data.status == 'error'){
                                            var messages = '';
                                            var count = 1;
                                            $.each(data.message, function( index, message ) {
                                                messages = messages + (count++) + '.' + message + '<br/>';
                                            });
                                            $.notify({
                                                icon: '{{asset('/assets/error.png')}}',
                                                title: '{{trans('board.notification.ups')}}',
                                                message: messages
                                            },{
                                                placement: {
                                                    from: "bottom",
                                                    align: "right"
                                                },
                                                type: 'minimalist',
                                                delay: 30000,
                                                newest_on_top: true,
                                                allow_dismiss: true,
                                                z_index: 99999999999,
                                                animate: {
                                                    enter: 'animated fadeInUp',
                                                    exit: 'animated fadeOutUp'
                                                },
                                                icon_type: 'image',
                                                template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                                '<img data-notify="icon" class="img-circle pull-left">' +
                                                '<span data-notify="title">{1}</span>' +
                                                '<span data-notify="message">{2}</span>' +
                                                '</div>'
                                            });
                                        }else if(data.status == 'ok'){
                                            window.location.href = "{{url('/board') }}" + '/' + data.response.board.id;
                                            //$('#main-content-wraper').foggy(false);
                                            //$( "#new-kanban" ).dialog( "close" ).position();
                                        }
                                    },
                                    'json'
                            );

                        }
                    }
                ],
                close: function(){
                    if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                        $('#main-content-wraper').foggy(false);
                    }else{
                        if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                            $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                        }
                    }
                }
            });

            @if(is_object($board) && !Auth::user()->isClient())
            $( "#new-child-kanban" ).dialog({
                "open": function() {
                    if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                        $('#main-content-wraper').foggy({
                            blurRadius: 8,          // In pixels.
                            opacity: 1,           // Falls back to a filter for IE.
                            cssFilterSupport: true  // Use "-webkit-filter" where available.
                        });
                    }else{
                        if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                            $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                        }
                    }
                },
                dialogClass: "no-close",
                autoOpen: false,
                modal: true,
                width: 400,
                resizable: false,
                dialogClass: 'main-dialog-class',
                hide: { effect: "fadein", duration: 1000 },
                buttons: [
                    {
                        text: "{{trans('board.widget_general.close')}}",
                        click: function() {
                            $('#main-content-wraper').foggy(false);
                            $( this ).dialog( "close" ).position();
                        }
                    },
                    {
                        text: "{{trans('board.widget_general.create')}}",
                        style: 'padding-right:0px;',
                        click: function() {
                            $.notifyClose();
                            var post_data = {
                                "_token": '{{ csrf_token() }}',
                                "board_id": '{{$board->id}}',
                                "event": 'new_child_board',
                                "department_id": $("#new-child-board-department").val(),
                                "template": $("#new-child-board-template").val(),
                            };

                            if($("#new-child-board-col").val() != null || $("#new-child-board-template").val() == 0){
                                post_data.columns = $("#new-child-board-col").val();
                            }

                            $.post(
                                    '{{ url('/api') }}',
                                    post_data,
                                    function( data ) {
                                        console.log(data);
                                        if(data.status == 'error') {
                                            var messages = '';
                                            var count = 1;
                                            $.each(data.message, function( index, message ) {
                                                messages = messages + (count++) + '.' + message + '<br/>';
                                            });
                                            ktNotification('{{trans('board.notification.failure')}}' , messages, 4600, false);
                                        }else if(data.status == 'ok'){
                                            window.location.href = "{{url('/board') }}" + '/' + data.response.board.id;
                                            //$('#main-content-wraper').foggy(false);
                                            //$( "#new-kanban" ).dialog( "close" ).position();
                                        }
                                    },
                                    'json'
                            );

                        }
                    }
                ],
                close: function(){
                    if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                        $('#main-content-wraper').foggy(false);
                    }else{
                        if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                            $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                        }
                    }
                }
            });
            $( "#edit-kanban" ).dialog({
                "open": function() {
                    if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                        $('#main-content-wraper').foggy({
                            blurRadius: 8,          // In pixels.
                            opacity: 1,           // Falls back to a filter for IE.
                            cssFilterSupport: true  // Use "-webkit-filter" where available.
                        });
                    }else{
                        if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                            $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                        }
                    }
                },
                dialogClass: "no-close",
                autoOpen: false,
                modal: true,
                width: 400,
                resizable: false,
                dialogClass: 'main-dialog-class',
                hide: { effect: "fadein", duration: 1000 },
                buttons: [
                    {
                        text: "{{trans('board.widget_general.close')}}",
                        click: function() {
                            $('#main-content-wraper').foggy(false);
                            $( this ).dialog( "close" ).position();;
                        }
                    },
                    {
                        text: "{{trans('board.widget_general.save')}}",
                        style: 'padding-right:0px;',
                        click: function() {
                            $.notifyClose();
                            var post_data = {
                                "_token": '{{ csrf_token() }}',
                                "event": 'edit_board',
                                "board_id": {{@$board->id}},
                                "name": $("#edit-board-name").val(),
                                "description": $("#edit-board-desc").val(),
                                "date": $("#edit-board-date").val(),
                                "department_id": $("#edit-board-department").val()
                            };

                            if($("#edit-board-col").val() != null || $("#edit-board-template").val() == 0){
                                post_data.columns = $("#edit-board-col").val();
                            }
                  
                            $.post(
                                    '{{ url('/api') }}',
                                    post_data,
                                    function( data ) {
                                        //console.log(data);
                                        if(data.status == 'error'){
                                            var messages = '';
                                            var count = 1;
                                            $.each(data.message, function( index, message ) {
                                                messages = messages + (count++) + '.' + message + '<br/>';
                                            });
                                            ktNotification('{{trans('board.notification.failure')}}' , messages, 4600, false);
                                        }else if(data.status == 'ok'){
                                            window.location.href = "{{url('/board') }}" + '/{{@$board->public_hash}}';
                                        }
                                    },
                                    'json'
                            );

                        }
                    }
                ],
                close: function(){
                    if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                        $('#main-content-wraper').foggy(false);
                    }else{
                        if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                            $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                        }
                    }
                }
            });

            $("#edit").click(function(){
                $( "#edit-kanban" ).dialog('open');
            });
            @endif
            @if(!Auth::user()->isClient())
            $("#make-public").click(function(){
                $("#unpublic-board").show();
                $("#public-board").hide();
                var board_id = $(this).attr('name');

                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'publish',
                    "hash": $("#boards-list").val(),
                };

                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'error'){
                                var messages = '';
                                var count = 1;
                                $.each(data.message, function( index, message ) {
                                    messages = messages + (count++) + '.' + message + '<br/>';
                                });
                                $.notify({
                                    icon: '{{asset('/assets/error.png')}}',
                                    title: '{{trans('board.notification.failure')}}',
                                    message: '{{trans('board.notification.admin_contact')}}'
                                },{
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    },
                                    type: 'minimalist',
                                    delay: 30000,
                                    newest_on_top: true,
                                    allow_dismiss: true,
                                    z_index: 99999999999,
                                    animate: {
                                        enter: 'animated fadeInUp',
                                        exit: 'animated fadeOutUp'
                                    },
                                    icon_type: 'image',
                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                    '<span data-notify="title">{1}</span>' +
                                    '<span data-notify="message">{2}</span>' +
                                    '</div>'
                                });
                            }else if(data.status == 'ok'){
                                $("#public_"+board_id).show();
                                $.notify({
                                    icon: '{{asset('/assets/ok.png')}}',
                                    title: '{{trans('board.notification.saved')}}',
                                    message: '{{trans('board.notification.board_public')}}'
                                },{
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    },
                                    type: 'minimalist',
                                    delay: 3000,
                                    newest_on_top: true,
                                    allow_dismiss: true,
                                    z_index: 99999999999,
                                    animate: {
                                        enter: 'animated fadeInUp',
                                        exit: 'animated fadeOutUp'
                                    },
                                    icon_type: 'image',
                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                    '<span data-notify="title">{1}</span>' +
                                    '<span data-notify="message">{2}</span>' +
                                    '</div>'
                                });
                            }
                        },
                        'json'
                );
            });

            $("#unpublish").click(function(){
                $("#unpublic-board").hide();
                $("#public-board").show();
                var board_id = $(this).attr('name');

                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'unpublish',
                    "hash": $("#boards-list").val(),
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'error'){
                                var messages = '';
                                var count = 1;
                                $.each(data.message, function( index, message ) {
                                    messages = messages + (count++) + '.' + message + '<br/>';
                                });
                                $.notify({
                                    icon: '{{asset('/assets/error.png')}}',
                                    title: '{{trans('board.notification.failure')}}',
                                    message: '{{trans('board.notification.admin_contact')}}'
                                },{
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    },
                                    type: 'minimalist',
                                    delay: 30000,
                                    newest_on_top: true,
                                    allow_dismiss: true,
                                    z_index: 99999999999,
                                    animate: {
                                        enter: 'animated fadeInUp',
                                        exit: 'animated fadeOutUp'
                                    },
                                    icon_type: 'image',
                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                    '<span data-notify="title">{1}</span>' +
                                    '<span data-notify="message">{2}</span>' +
                                    '</div>'
                                });
                            }else if(data.status == 'ok'){
                                $("#public_"+board_id).hide();
                                $.notify({
                                    icon: '{{asset('/assets/ok.png')}}',
                                    title: '{{trans('board.notification.saved')}}',
                                    message: '{{trans('board.notification.board_unpublished')}}'
                                },{
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    },
                                    type: 'minimalist',
                                    delay: 3000,
                                    newest_on_top: true,
                                    allow_dismiss: true,
                                    z_index: 99999999999,
                                    animate: {
                                        enter: 'animated fadeInUp',
                                        exit: 'animated fadeOutUp'
                                    },
                                    icon_type: 'image',
                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                    '<span data-notify="title">{1}</span>' +
                                    '<span data-notify="message">{2}</span>' +
                                    '</div>'
                                });
                            }
                        },
                        'json'
                );
            });

//            $("#user-tasks").click(function() {
//                $(".all-tasks").show();
//                $(".user-tasks").hide();
//            });
//
//            $("#all-tasks").click(function() {
//                $(".user-tasks").show();
//                $(".all-tasks").hide();
//            });

            $("#lock").click(function() {
                $("#unlock-board").show();
                $("#lock-board").hide();
                var board_id = $(this).attr('name');

                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'lock',
                    "hash": $("#boards-list").val(),
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'error'){
                                var messages = '';
                                var count = 1;
                                $.each(data.message, function( index, message ) {
                                    messages = messages + (count++) + '.' + message + '<br/>';
                                });
                                $.notify({
                                    icon: '{{asset('/assets/error.png')}}',
                                    title: '{{trans('board.notification.failure')}}',
                                    message: '{{trans('board.notification.admin_contact')}}'
                                },{
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    },
                                    type: 'minimalist',
                                    delay: 30000,
                                    newest_on_top: true,
                                    allow_dismiss: true,
                                    z_index: 99999999999,
                                    animate: {
                                        enter: 'animated fadeInUp',
                                        exit: 'animated fadeOutUp'
                                    },
                                    icon_type: 'image',
                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                    '<span data-notify="title">{1}</span>' +
                                    '<span data-notify="message">{2}</span>' +
                                    '</div>'
                                });
                            }else if(data.status == 'ok'){
                                $("#lock_"+board_id).show();
                                $.notify({
                                    icon: '{{asset('/assets/ok.png')}}',
                                    title: '{{trans('board.notification.saved')}}',
                                    message: '{{trans('board.notification.board_locked')}}'
                                },{
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    },
                                    type: 'minimalist',
                                    delay: 5000,
                                    newest_on_top: true,
                                    allow_dismiss: true,
                                    z_index: 99999999999,
                                    animate: {
                                        enter: 'animated fadeInUp',
                                        exit: 'animated fadeOutUp'
                                    },
                                    icon_type: 'image',
                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                    '<span data-notify="title">{1}</span>' +
                                    '<span data-notify="message">{2}</span>' +
                                    '</div>'
                                });
                                gridster.disable();
                            }
                        },
                        'json'
                );
            });

            $("#unlock").click(function() {
                $("#lock-board").show();
                $("#unlock-board").hide();
                var board_id = $(this).attr('name');

                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'unlock',
                    "hash": $("#boards-list").val(),
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'error'){
                                var messages = '';
                                var count = 1;
                                $.each(data.message, function( index, message ) {
                                    messages = messages + (count++) + '.' + message + '<br/>';
                                });
                                $.notify({
                                    icon: '{{asset('/assets/error.png')}}',
                                    title: '{{trans('board.notification.failure')}}',
                                    message: '{{trans('board.notification.admin_contact')}}'
                                },{
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    },
                                    type: 'minimalist',
                                    delay: 30000,
                                    newest_on_top: true,
                                    allow_dismiss: true,
                                    z_index: 99999999999,
                                    animate: {
                                        enter: 'animated fadeInUp',
                                        exit: 'animated fadeOutUp'
                                    },
                                    icon_type: 'image',
                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                    '<span data-notify="title">{1}</span>' +
                                    '<span data-notify="message">{2}</span>' +
                                    '</div>'
                                });
                            }else if(data.status == 'ok'){
                                $("#lock_"+board_id).hide();
                                $.notify({
                                    icon: '{{asset('/assets/ok.png')}}',
                                    title: '{{trans('board.notification.saved')}}',
                                    message: '{{trans('board.notification.board_unlocked')}}'
                                },{
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    },
                                    type: 'minimalist',
                                    delay: 5000,
                                    newest_on_top: true,
                                    allow_dismiss: true,
                                    z_index: 99999999999,
                                    animate: {
                                        enter: 'animated fadeInUp',
                                        exit: 'animated fadeOutUp'
                                    },
                                    icon_type: 'image',
                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                    '<span data-notify="title">{1}</span>' +
                                    '<span data-notify="message">{2}</span>' +
                                    '</div>'
                                });
                                gridster.enable();
                            }
                        },
                        'json'
                );
            });
            @endif

            $( "#note-preview" ).dialog({
                "open": function(event, ui) {
                    $("#work_stream").html('');
                    if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                        $('#main-content-wraper').foggy({
                            blurRadius: 8,          // In pixels.
                            opacity: 1,           // Falls back to a filter for IE.
                            cssFilterSupport: true  // Use "-webkit-filter" where available.
                        });
                    }else{
                        if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                            $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                        }
                    }
                    $(".ui-dialog").block({
                        message: '<img src="{{ asset('/assets/loading.gif') }}"/>',
                        css: {
                            backgroundColor: 'none',
                            opacity: 0.9,
                            border: '0px'
                        },
                        overlayCSS: {
                            backgroundColor: 'white',
                            opacity: 0.9,
                        }
                    });
                },
                dialogClass: "no-close",
                autoOpen: false,
                modal: true,
                width: 800,
                resizable: false,
                dialogClass: 'main-dialog-class',
                hide: { effect: "fadein", duration: 1000 },
                close: function(){
                    if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                        $('#main-content-wraper').foggy(false);
                    }else{
                        if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                            $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                        }
                    }
                }
            });

            $(document).on('dblclick', '.gs-w', function(){
                $( "#note-preview" ).dialog('open');
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'get_task',
                    "task_id": $(this).attr('id'),
                    "board_id": '{{@$board->id}}'
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            console.log(data);
                            if(data.status == 'ok') {
                                var task = data.response.task;
                                $("#note-preview").dialog('option', 'title', '{{trans('board.task_title')}} ' + task.id);
                                $("#task_description_preview").summernote('code', task.description);
                                $(".subject_preview").html(task.subject);
                                $("#subject_preview").val(task.subject);
                                $("#task_id_h").val(task.id);
       
                                if(task.public_hash != '' && task.public_hash != null && task.public_hash != 0) {
                                    $("#board_edit").val(task.public_hash);
                                    $(".board_change").html($("#board_edit option:selected").text());
                                }else{
                                    $(".board_edit").html("Backlog");
                                    $("#board_edit").val('NULL');
                                }

                                if(task.project_id != '' && task.project_id != null && task.project_id != 0) {
                                    $("#project_edit").val(task.project_id);
                                    $(".project_edit").html($("#project_edit option:selected").text());
                                }else{
                                    $(".project_edit").html("Unknown Project");
                                    $("#project_edit").val('');
                                }

                                if(task.type != '' && task.type != null && task.type != 0) {
                                    //console.log(task);
                                    $("#type_edit").val(task.type);
                                    $(".type_edit").html($("#type_edit option:selected").text());
                                }else{
                                    $(".type_edit").html("Unknown Type");
                                    $("#type_edit").val('');
                                }

                                if(task.priority != '' && task.priority != null && task.priority != 0) {
                                    $("#priority_edit").val(task.priority);
                                    $(".priority_edit").html($("#priority_edit option:selected").text());
                                }else{
                                    $(".priority_edit").html("Not prioritized");
                                    $("#priority_edit").val('');
                                }

                                if(task.user_id != '' && task.user_id != null && task.user_id != 0) {
                                    $("#user_edit").val(task.user_id);
                                    $(".user_edit").html($("#user_edit option:selected").text());
                                }else{
                                    $(".user_edit").html("Not Assigned");
                                    $("#user_edit").val('');
                                }

                                if(task.estimate != '' && task.estimate != null && task.estimate != 0) {
                                    $(".estimate_preview").html(task.estimate);
                                    $("#estimate_input_task_change").val(task.estimate);
                                }else{
                                    $(".estimate_preview").html("No estimate");
                                    $("#estimate_input_task_change").val('');
                                }

                                if(task.first_name != '' && task.first_name != null && task.first_name != 0 ||
                                    task.last_name != '' && task.last_name != null && task.last_name != 0 
                                    ) {
                                    $("#manager").html(task.first_name + ' ' + task.last_name);
                                    $("#manager_edit").val(task.manager_id);
                                }else{
                                    $(".manager").html("No manager");
                                    $("#manager_edit").val('');
                                }

                                $("#task_state").html(tmp_columns[task.size_y - 1].toUpperCase());

                                $.each(data.response.comments, function( index, comment ) {
                                    //console.log(comment)
                                    $("#work_stream").prepend('<div id="comment_'+comment.id+'" class="row" style="margin: 0px !important;font-size:13px;border-bottom: 1px solid #DBDBDB;padding:20px;text-align:justify;"> <div class="col-md-12" > <div class="box-tools pull-left" style="padding-right:20px;"> <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image='+ comment.avatar +'" class="user-image-task" alt="User Image"></div><div><strong>' + comment.author + '.</strong>: '+ comment.comment + '</div><span class="direct-chat-timestamp pull-right" style="padding-left:10px;"><a href="#" class="delwscomment" style="color:gray;" title="{{trans('board.comment_delete')}}" id="'+comment.id+'"><i class="ion-trash-a"></i></a></span><span class="direct-chat-timestamp pull-right">'+comment.comment_date+'</span></div></div>')
                                });

                                $(".ui-dialog").unblock();

                            }else{
                                $( "#note-preview" ).dialog('close');
                                $.notify({
                                    icon: '{{asset('/assets/ok.png')}}',
                                    title: '{{trans('board.notification.error')}}',
                                    message: '{{trans('board.notification.task_can_not_delete')}}'
                                },{
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    },
                                    type: 'minimalist',
                                    delay: 5000,
                                    newest_on_top: true,
                                    allow_dismiss: true,
                                    z_index: 99999999999,
                                    animate: {
                                        enter: 'animated fadeInUp',
                                        exit: 'animated fadeOutUp'
                                    },
                                    icon_type: 'image',
                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                    '<span data-notify="title">{1}</span>' +
                                    '<span data-notify="message">{2}</span>' +
                                    '</div>'
                                });
                            }
                        },
                        'json'
                );
            });
            @if(!Auth::user()->isClient())
            $(document).on('click', '#new-task', function(){
                $( "#new-note" ).dialog('open');
            });

            $(document).on('click', '#add-new-board', function(){
                $( "#new-kanban" ).dialog('open');
            });

            $(document).on('click', '#add-child-board', function(){
                $( "#new-child-kanban" ).dialog('open');
            });
            @endif
            $(document).keyup(function(e) {
                if (e.keyCode == 27) {
                    if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                        $('#main-content-wraper').foggy(false);
                    }
                }
            });

            $("#board_hash").click(function(){
                $(".other-columns").hide();
                $("."+$(this).val()).show();
            });

            $('#boards-list').select2({
                data:[@foreach($boards_list as $board_entity){id:'{{$board_entity->public_hash}}',text:'<div  class="res">{{strtoupper($board_entity->name)}} @if(!empty($board_entity->department_name) || is_object($board_entity->parent_board) || is_object($board_entity->child_board)){{(!empty($board_entity->department_name))?' : ' . strtoupper($board_entity->department_name):''}}@endif</div><div style="color:black;font-size:12px;">@if(!empty($board_entity->department_name) || is_object($board_entity->parent_board) || is_object($board_entity->child_board))<span class="res_selected"><strong>{{(!empty($board_entity->department_name))?strtoupper($board_entity->department_name):trans('board.unknown_department')}}</strong> : </span>@endif{{$board_entity->description}}@if($board_entity->start_date !==  null&& $board_entity->end_date !== null) starts {{date('d/m/Y', strtotime($board_entity->start_date))}} and ends {{date('d/m/Y', strtotime($board_entity->end_date))}} @endif @if($board_entity->public == 1) <i id="public_{{$board_entity->id}}" class="ion-eye" style="font-size:12px;color:gray;padding-left:5px;"></i> @else <i id="public_{{$board_entity->id}}" class="ion-eye" style="font-size:12px;color:gray;padding-left:5px;display:none;"></i> @endif @if($board_entity->lock == 1) <i id="lock_{{$board_entity->id}}" class="ion-ios-locked" style="font-size:12px;color:gray;padding-left:5px;"></i> @else <i id="lock_{{$board_entity->id}}" class="ion-ios-locked" style="font-size:12px;color:gray;padding-left:5px;display:none;"></i>@endif @if($board_entity->default == 1) <i id="default_{{$board_entity->id}}" class="ion-android-star" style="font-size:12px;color:gray;padding-left:5px;"></i> @else <i id="default_{{$board_entity->id}}" class="ion-android-star" style="font-size:12px;color:gray;padding-left:5px;display:none;"></i>@endif</div>', value:'{{$board_entity->public_hash}}'},@endforeach],
                dropdownCssClass: "bigdrop",
                placeholder: "{{trans('board.board_search')}}",
                allowClear: false,
                escapeMarkup: function (m) { return m; }
            });

            @if(is_object($board))
            $('#boards-list').select2('val', '{{ $board->public_hash }}');
            @endif

            //Tittle bug fix
            $(".select2-selection__rendered").attr("title","");

            $("#boards-list").change(function(){
                window.location.href = "{{url('/board') }}" + '/' + $(this).val();
            });

            $(".search").click(function(){
               if($("#board-search").css('display') == 'none'){
                   $("#user-filter").hide();
                   $("#board-filter").show();
                   $("#board-search").show();
                   $("#user-search").hide();
               }else{
                   $("#user-filter").show();
                   $("#board-filter").hide();
                   $("#board-search").hide();
                   $("#user-search").show();
                   $("#search-user-select").select2('open');
               }
            });

            $("#search-user-select").change(function(){
                window.location.href = "{{url('/board') }}" + '/' + $(this).val() + '{{@$hash}}';
            });

            $('#search-user-select').select2({
                data:[@foreach($users as $user){id:'{{$user->id}}',text:'<div style="width:100%;">{{$user->first_name}} {{$user->last_name}}</div>', value:'{{$user->id}}'},@endforeach],
                dropdownCssClass: "bigdrop",
                placeholder: "{{trans('board.filter_board')}}",
                allowClear: false,
                escapeMarkup: function (m) { return m; }
            });

            $('.dropdown-menu').click(function(e) {
                e.stopPropagation();
            });

           $("#new-board-template").change(function(){
               id = $(this).val();
               if(id == 0){
                   $('#custom_col').show();
               }else{
                   $('#custom_col').hide();
               }
           });

            $('#new-board-template').select2({
                data:[
                    {id:0,text:'<div style="width:100%;font-size:12px;text-align:center;">{{trans('board.custom_board')}}</div>', value:'custom_col'},@foreach($board_templates as $board_template){id:{{$board_template->id}},text:'<div>{{strtoupper($board_template->name)}}</div><div style="color:black;font-size:10px;" class="res">{{strtolower(implode(', ', json_decode($board_template->columns)))}}</div>', value:'{{$board_template->id}}'}, @endforeach
                ],
                dropdownCssClass: "bigdrop",
                placeholder: "{{trans('board.board_select_or_customize')}}",
                allowClear: false,
                escapeMarkup: function (m) { return m; }
            });

            $('#new-board-department').select2({
                data:[
                    {id:0,text:'<div style="width:100%;font-size:12px;text-align:center;">{{trans('board.no_department')}}</div>', value:'custom_col'},@foreach($departments as $department){id:{{$department->id}},text:'<div>{{strtoupper($department->name)}}</div>', value:'{{$department->id}}'}, @endforeach
                ],
                dropdownCssClass: "bigdrop",
                placeholder: "{{trans('board.department_select')}}",
                allowClear: false,
                escapeMarkup: function (m) { return m; }
            });



            $("#new-child-board-template").change(function(){
                id = $(this).val();
                if(id == 0){
                    $('#child-custom_col').show();
                }else{
                    $('#child-custom_col').hide();
                }
            });

            $('#new-child-board-template').select2({
                data:[
                    {id:0,text:'<div style="width:100%;font-size:12px;text-align:center;">{{trans('board.custom_board')}}</div>', value:'custom_col'},@foreach($board_templates as $board_template){id:{{$board_template->id}},text:'<div>{{strtoupper($board_template->name)}}</div><div style="color:black;font-size:10px;" class="res">{{strtolower(implode(', ', json_decode($board_template->columns)))}}</div>', value:'{{$board_template->id}}'}, @endforeach
                ],
                dropdownCssClass: "bigdrop",
                placeholder: "{{trans('board.board_select_or_customize')}}",
                allowClear: false,
                escapeMarkup: function (m) { return m; }
            });

            @if(!Auth::user()->isClient())
            $("#default").click(function() {
                $("#undefault-board").show();
                $("#default-board").hide();
                var board_id = $(this).attr('name');
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'undefault',
                    "hash": $("#boards-list").val(),
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'error'){
                                var messages = '';
                                var count = 1;
                                $.each(data.message, function( index, message ) {
                                    messages = messages + (count++) + '.' + message + '<br/>';
                                });
                                $.notify({
                                    icon: '{{asset('/assets/error.png')}}',
                                    title: '{{trans('board.notification.failure')}}',
                                    message: '{{trans('board.notification.admin_contact')}}'
                                },{
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    },
                                    type: 'minimalist',
                                    delay: 30000,
                                    newest_on_top: true,
                                    allow_dismiss: true,
                                    z_index: 99999999999,
                                    animate: {
                                        enter: 'animated fadeInUp',
                                        exit: 'animated fadeOutUp'
                                    },
                                    icon_type: 'image',
                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                    '<span data-notify="title">{1}</span>' +
                                    '<span data-notify="message">{2}</span>' +
                                    '</div>'
                                });
                            }else if(data.status == 'ok'){
                                $("#default_"+board_id).hide();
                                ktNotification('{{trans('board.notification.saved')}}', '{{trans('board.notification.board_set_as_not_default')}}', 2000, false);
                            }
                        },
                        'json'
                );
            });

            $("#undefault").click(function() {
                $("#default-board").show();
                $("#undefault-board").hide();
                var board_id = $(this).attr('name');
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'default',
                    "hash": $("#boards-list").val(),
                };
                $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'error'){
                                var messages = '';
                                var count = 1;
                                $.each(data.message, function( index, message ) {
                                    messages = messages + (count++) + '.' + message + '<br/>';
                                });
                                $.notify({
                                    icon: '{{asset('/assets/error.png')}}',
                                    title: '{{trans('board.notification.failure')}}',
                                    message: '{{trans('board.notification.admin_contact')}}'
                                },{
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    },
                                    type: 'minimalist',
                                    delay: 30000,
                                    newest_on_top: true,
                                    allow_dismiss: true,
                                    z_index: 99999999999,
                                    animate: {
                                        enter: 'animated fadeInUp',
                                        exit: 'animated fadeOutUp'
                                    },
                                    icon_type: 'image',
                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                    '<span data-notify="title">{1}</span>' +
                                    '<span data-notify="message">{2}</span>' +
                                    '</div>'
                                });
                            }else if(data.status == 'ok'){
                                $("#default_"+board_id).show();
                                ktNotification('{{trans('board.notification.saved')}}', '{{trans('board.notification.board_set_as_default')}}', 2000, true);
                            }
                        },
                        'json'
                );
            });

            @if(is_object($board))
            $('#edit-board-department').select2('val', '{{ $board->department_id }}');
            @endif

            $(".js-example-tags").select2({
                tags: true,
                placeholder: "{{trans('board.column_name')}}"
            });

            $('.gs-w').mousedown(function(){
                $(this).qtip("hide");
            });

            $('.realtask').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.drag_and_drop')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });


            $('#w_stream_extended').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.workstream_maximize')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#search').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.filter')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });



            $("#w_stream_extended").click(function(){
                $(".task_details").hide();
                $("#work_stream").css('height','470px');
                $("#stream_label").html($(".task_edit").html());
                $(this).hide();
                $("#w_stream_small").show();
            });

            $("#w_stream_small").click(function(){
                $("#work_stream").css('height','200px');
                $(this).hide();
                $("#stream_label").html('{{trans('board.workstream')}}');
                $("#w_stream_extended").show();
                $(".task_details").show();
            });

            $('#w_stream_small').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.workstream_minimize')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#estimate_input_task').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '<div style="font-size:9px;"><strong>{{trans('board.tooltip.legend.title')}}</strong><br/>{{trans('board.tooltip.legend.years')}}<br/>{{trans('board.tooltip.legend.months')}}<br/>{{trans('board.tooltip.legend.weeks')}}<br/>{{trans('board.tooltip.legend.days')}}<br/>{{trans('board.tooltip.legend.hours')}}<br/>{{trans('board.tooltip.legend.minutes')}}</div>'
                },
                show: { ready: false, event: 'focus'},
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                },
                hide: {
                    event: 'blur'
                }
            });

            $('#estimate_input_task_change').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.legend.save')}}<br/><br/><div style="font-size:9px;"><strong>{{trans('board.tooltip.legend.title')}}</strong><br/>{{trans('board.tooltip.legend.years')}}<br/>{{trans('board.tooltip.legend.months')}}<br/>{{trans('board.tooltip.legend.weeks')}}<br/>{{trans('board.tooltip.legend.days')}}<br/>{{trans('board.tooltip.legend.hours')}}<br/>{{trans('board.tooltip.legend.minutes')}}</div>'
                },
                show: { ready: false, event: 'focus'},
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                },
                hide: {
                    event: 'blur'
                }
            });


            $('.col-edit-input').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.save')}}'
                },
                show: { ready: false, event: 'focus'},
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                },
                hide: {
                    event: 'blur'
                }
            });

            $('#estimate_input_n_task').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '<div style="font-size:10px;"><strong>{{trans('board.tooltip.legend.title')}}</strong><br/>{{trans('board.tooltip.legend.years')}}<br/>{{trans('board.tooltip.legend.months')}}<br/>{{trans('board.tooltip.legend.weeks')}}<br/>{{trans('board.tooltip.legend.days')}}<br/>{{trans('board.tooltip.legend.hours')}}<br/>{{trans('board.tooltip.legend.minutes')}}</div>'
                },
                show: { ready: false, event: 'focus'},
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                },
                hide: {
                    event: 'blur'
                }
            });

            @if(is_object($board))
             var ready_tip = false;
            @else
              var ready_tip = true;
            @endif

            $('#add-new-board').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.create_board')}}'
                },
                show: { ready: ready_tip },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#add-child-board').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.create_child_board')}}'
                },
                show: { ready: ready_tip },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#board-filter').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.filter_per_user')}}'
                },
                show: { ready: ready_tip },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#user-filter').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.search_board')}}'
                },
                show: { ready: ready_tip },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#make-public').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.board_public_title')}} <div style="font-size:9px;">{{trans('board.tooltip.board_public_text')}}</div>'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#unpublish').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.board_unpublish')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#new-task').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.new_task_note')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#all-tasks').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.team_perspective')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#user-tasks').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.my_perspective')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('.task_edit').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.double_click')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#lock-board').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.lock_board')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#unlock-board').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.unlock_board')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#board_change').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '<div style="text-align:center;">{{trans('board.tooltip.board_change1')}}<br/>{{trans('board.tooltip.board_change2')}}</div>'
                },
                show: { ready: false},
                position: {
                    my: 'bottom center',  // Position my top left...
                    at: 'top center', // at the bottom right of...
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#clear-board').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.clear_all_tasks')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#delete-board').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.delete_board')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#default-board').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.board_set_not_default')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#undefault-board').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('board.tooltip.board_set_default')}}'
                },
                show: { ready: false },
                position: {
                    my: 'top center',  // Position my top left...
                    at: 'bottom center', // at the bottom right of...
                },
                hide: {
                    when: { event: 'click' },
                    effect: { type: 'slide' }
                },
                style: {
                    classes: 'qtip-light qtip-shadow'
                }
            });

            $('#new-board-date').daterangepicker({format: 'DD/MM/YYYY'});
            $('#edit-board-date').daterangepicker({format: 'DD/MM/YYYY'});

            $(document).on('dblclick', '.task_edit', function(){
                $(this).hide();
                $('#' + $(this).attr('id') + '_change' ).show();
            });

            $("#board_change").change(function(){
                if(($(this).val()) != '{{str_replace('/','',$hash)}}') {
                    $( "#note-preview" ).dialog('close');
                    gridster.remove_widget('#' + $("#task_id_h").val());
                }
            });

            $(".estimate_input").inputmask("Regex", {
                regex: "([0-9]+y)?([0-9]+mo)?([0-9]+w)?([0-9]+d)?([0-9]+h)?([0-9]+m)?"
            });

            $("#estimate_input_task_change").inputmask("Regex", {
                regex: "([0-9]+y)?([0-9]+mo)?([0-9]+w)?([0-9]+d)?([0-9]+h)?([0-9]+m)?"
            });

            $("#new-task").mousedown(function(event){
                event.preventDefault();
                event.stopPropagation();
                //TODO: Implement the task widget...
                return false;
            });
            @endif

            var note_toolbar = [
                        ['font', ['bold', 'underline']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['fullscreen']]];

            $('textarea#task_description').summernote({
                height: 200,                 // set editor height
                minHeight: null,             // set minimum height of editor
                maxHeight: null,             // set maximum height of editor
                focus: true,                  // set focus to editable area after initializing summernote
                toolbar: note_toolbar
            });

            $('textarea#task_description_preview').summernote({
                height: 200,                 // set editor height
                minHeight: null,             // set minimum height of editor
                maxHeight: null,             // set maximum height of editor
                focus: true,                  // set focus to editable area after initializing summernote
                toolbar: note_toolbar
            });

            @if(!Auth::user()->isClient() && is_object($board))
            $(document).keypress( function(e) {
                if(e.keyCode == 13 && e.ctrlKey == true && $('.note-editable').is(":focus") == true)
                {
                    event.preventDefault();
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'desc_update',
                        "hash": $("#boards-list").val(),
                        "task_id": $("#task_id_h").val(),
                        "description": $('#task_description_preview').val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                //console.log(data);
                                if(data.status == 'ok'){
                                    ktNotification('{{trans('board.notification.description_saved')}}', '', 2000, true);
                                    $("#"+$("#task_id_h").val()+" #t_desc").html(data.raw_text.substr(0, 200)+'...');
                                }else{
                                    ktNotification('{{trans('board.notification.error')}}', data.message, 2000, false);
                                }
                            },
                            'json'
                    );
                }
            });

            @endif

            /* SELECT2 FIX*/
            if ($.ui && $.ui.dialog && $.ui.dialog.prototype._allowInteraction) {
                var ui_dialog_interaction = $.ui.dialog.prototype._allowInteraction;
                $.ui.dialog.prototype._allowInteraction = function(e) {
                    if ($(e.target).closest('.select2-dropdown').length) return true;
                    return ui_dialog_interaction.apply(this, arguments);
                };
            }

        });
    </script>

</div>
</body>
@endsection
