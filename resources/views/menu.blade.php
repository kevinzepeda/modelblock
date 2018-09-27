<?php
$layoutColors = \Auth::user()->getSystemColors();
?>
<div style="background-color:#909090;background-color:{{$layoutColors['layout_color']}};">
    <header class="main-header" style="max-width: 1100px;margin: 0 auto;text-align:left;background-color:{{$layoutColors['layout_color']}} !important;">
        <a href="{{url('/')}}" class="logo" style="background-color:{{$layoutColors['layout_color']}} !important;color:{{$layoutColors['text_color']}} !important;">
            @if($systemLogo = Auth::user()->getSystemLogo() && file_exists(Auth::user()->getSystemLogo()))
                <img src="{{ url('/api') }}?event=get_system_logo&_token={{ csrf_token() }}&image={{$systemLogo}}" style="max-width:200px;max-height:52px;"/>
            @else
                <?php echo \App\Models\ktUser::getSystemLabel(); ?>
            @endif
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation" style="background-color:{{$layoutColors['layout_color']}} !important;">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <i class="ion-navicon-round"></i>
            </a>
            <!-- Sidebar toggle button-->
            <div class="navbar-custom-menu mmenu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->

                    @if(Config::get('app.features.timesheets') && !Auth::user()->isClient())
                        <li class="dropdown messages-menu menu-entity">
                            <a href="{{ url('/home') }}" style="color:{{$layoutColors['text_color']}} !important;">
                                {{trans('application.main_menu.time')}}
                            </a>
                        </li>
                        <li style="border-right:1px solid #969696;width:1px;height: 20px !important;margin-top:15px;opacity:0.4;"></li>
                    @endif
                    @if(Config::get('app.features.boards'))
                        <li class="dropdown notifications-menu menu-entity">
                            <a href="{{ url('/board') }}" style="color:{{$layoutColors['text_color']}} !important;">
                                {{trans('application.main_menu.board')}}
                            </a>
                        </li>
                        <li style="border-right:1px solid #969696;width:1px;height: 20px !important;margin-top:15px;opacity:0.4;"></li>
                    @endif
                    @if(Auth::user()->isClient())
                        <li class="dropdown notifications-menu menu-entity">
                            <?php $client = \App\Models\ktUser::getUserData(); ?>
                            <a href="{{ url('/office/customer/'.$client->customer_id) }}" style="color:{{$layoutColors['text_color']}} !important;">
                                ACCOUNT
                            </a>
                        </li><!-- end task item -->
                        <li style="border-right:1px solid #969696;width:1px;height: 20px !important;margin-top:15px;opacity:0.4;"></li>
                    @endif
                    @if((Config::get('app.features.finances') || Config::get('app.features.projects') || Config::get('app.features.customers')) && !Auth::user()->isClient())
                        <li class="dropdown tasks-menu user user-menu menu-entity">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:{{$layoutColors['text_color']}} !important;">
                                {{trans('application.main_menu.office')}}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">

                                        @if((Config::get('app.features.customers') && (Auth::user()->canManage() || Auth::user()->isAdmin())))
                                            <li><!-- Task item -->
                                                <a href="{{ url('/office/customer') }}">
                                                    <h3>{{trans('application.main_menu.customers')}}</h3>
                                                </a>
                                            </li><!-- end task item -->
                                        @endif

                                        @if(Config::get('app.features.projects'))
                                            <li><!-- Task item -->
                                                <a href="{{ url('/office/project') }}">
                                                    <h3>{{trans('application.main_menu.projects')}}</h3>
                                                </a>
                                            </li><!-- end task item -->
                                        @endif

                                        @if((Config::get('app.features.finances') && (Auth::user()->canManage() || Auth::user()->isAdmin())))
                                            <li><!-- Task item -->
                                                <a href="{{ url('/office/finance') }}">
                                                    <h3>{{trans('application.main_menu.finance')}}</h3>
                                                </a>
                                            </li><!-- end task item -->
                                        @endif
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li style="border-right:1px solid #969696;width:1px;height: 20px !important;margin-top:15px;opacity:0.4;"></li>
                    @endif
                    @if((Config::get('app.features.reports') && (Auth::user()->canManage() || Auth::user()->isAdmin())) && !Auth::user()->isClient())
                        <li class="dropdown tasks-menu user user-menu menu-entity">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:{{$layoutColors['text_color']}} !important;">
                                {{trans('application.main_menu.reports')}}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li><!-- Task item -->
                                            <a href="{{ url('/invoice/reports') }}">
                                                <h3>{{trans('application.main_menu.finance')}}</h3>
                                            </a>
                                        </li>
                                        <!-- end task item -->
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if((Auth::user()->canManage() || Auth::user()->isAdmin()))
                    <li class="dropdown notifications-menu">
                        <!-- Menu toggle button -->
                        <?php $notifications = \App\Models\ktSettings::getSubmitedQuestionnaries(); ?>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"
                           style="color:{{$layoutColors['text_color']}} !important;">
                            <i class="ion ion-earth" style="font-size:16px;"></i>
                            @if(count($notifications) > 0)
                                <span class="label label-success" style="font-size:9px;">{{count($notifications)}}</span>
                            @endif
                        </a>
                        @if(count($notifications) > 0)
                            <ul class="dropdown-menu">
                                <li>
                                    <!-- Inner Menu: contains the notifications -->
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; max-height: 200px; min-height: 50px;">
                                        <ul class="menu" style="width: 100%; max-height: 200px; min-height: 50px;">
                                            @foreach($notifications as $notifiation)
                                            <li><!-- start notification -->
                                                <a href="#" onclick="location.href='{{url('/quote/review/'.$notifiation->id)}}'"
                                                   style="padding-top:0px !important;margin-top:0px !important;margin-bottom:0px !important;padding-bottom:0px !important;">
                                                    <div class="pull-left">
                                                        <i class="ion ion-ios-help" style="font-size:30px;color:#888888 !important;padding-top:11px;padding-right:10px;"></i>
                                                    </div>
                                                    <h4 style="font-size:14px;padding-bottom:0px !important;margin-bottom:0px !important;">
                                                        <?php $submiterName = \App\Models\ktQuote::getSubmitter($notifiation->target, $notifiation->reference_id);?>
                                                        @if(!empty($submiterName))
                                                            {{$submiterName}}
                                                        @else
                                                            {{trans('application.notifications.new_client')}}
                                                        @endif
                                                        <small style="float:right;font-size:10px !important;">
                                                            <i class="fa fa-clock-o" style="font-size:10px;"></i>
                                                            @if(strtotime($notifiation->submission_date) >= strtotime("today"))
                                                                {{trans('application.notifications.today')}}
                                                            @elseif(strtotime($notifiation->submission_date) >= strtotime("yesterday"))
                                                                {{trans('application.notifications.yesterday')}}
                                                            @else
                                                                {{date("d.m.Y", strtotime($notifiation->submission_date))}}
                                                            @endif
                                                        </small>
                                                    </h4>
                                                    <p style="color:#888888 !important;font-size:12px;">
                                                        {{trans('application.notifications.submitted_a')}}
                                                        @if($notifiation->target !== 'PROJECT')
                                                            {{trans('application.notifications.project_related')}}
                                                        @elseif($notifiation->target !== 'CUSTOMER')
                                                            {{trans('application.notifications.new_order')}}
                                                        @endif
                                                        {{trans('application.notifications.form')}}
                                                    </p>
                                                </a>
                                            </li>
                                            @endforeach
                                            <!-- end notification -->
                                        </ul>
                                    </div>
                                </li>
                                {{--<li class="footer"><a href="#">View all</a></li>--}}
                            </ul>
                        @else
                            <ul class="dropdown-menu">
                                <li>
                                    <!-- Inner Menu: contains the notifications -->
                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 30px;">
                                        <ul class="menu" style="width: 100%; height: 30px;text-align:center;padding-top:6px;">
                                            <li style="color:#888888 !important;font-size:12px;">
                                                {{trans('application.notifications.no_notifications')}}
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                {{--<li class="footer"><a href="#">View all</a></li>--}}
                            </ul>
                        @endif
                    </li>
                    @endif
                    <li style="border-right:1px solid #969696;width:1px;height: 20px !important;margin-top:15px;opacity:0.4;"></li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown tasks-menu user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:{{$layoutColors['text_color']}} !important;">
                            <i class="ion ion-ios-arrow-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">
                                @if($data->avatar != NULL)
                                    <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image={{$data->avatar}}"
                                         class="user-image profile-image" alt="User Image"
                                         style="width:20px;height:20px;margin-top:2px;"/>
                                @else
                                    <img src="{{ asset('/assets/dist/img/profile-placeholder.png') }}"
                                         class="user-image profile-image" alt="User Image"
                                         style="width:20px;height:20px;margin-top:2px;"/>
                                @endif
                                {{\Auth::user()->getFullName()}}
                            </li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <li><!-- Task item -->
                                        <a href="{{ url('/settings/profile') }}">
                                            <h3>{{trans('application.main_menu.edit_profile')}}</h3>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                    @if(Auth::user()->isAdmin())
                                        <li><!-- Task item -->
                                            <a href="{{ url('/settings') }}">
                                                <h3>{{trans('application.main_menu.settings')}}</h3>
                                            </a>
                                        </li><!-- end task item -->
                                    @endif
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="{{ url('/auth/logout') }}">{{trans('application.main_menu.logout')}}</a>
                            </li>
                        </ul>
                    </li>

                    @if(Config::get('app.features.multilanguage'))
                        <li class="dropdown tasks-menu user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                ENG
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Change the language</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <h3>English</h3>
                                            </a>
                                        </li>
                                        <!-- end task item -->
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <h3>Deutsch</h3>
                                            </a>
                                        </li>
                                        <!-- end task item -->
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <h3>Srpski</h3>
                                            </a>
                                        </li>
                                        <!-- end task item -->
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 674px;">
            <div class="sidebar" id="scrollspy" style="height: 674px; overflow: hidden; width: auto;">

                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="nav sidebar-menu">
                    @if(Config::get('app.features.timesheets'))
                        <li><a href="{{ url('/') }}"><i
                                        class="fa fa-chevron-circle-right"></i> {{trans('application.main_menu.time')}}
                            </a></li>
                    @endif

                    @if(Config::get('app.features.boards'))
                        <li><a href="{{ url('/board') }}"><i
                                        class="fa fa-chevron-circle-right"></i> {{trans('application.main_menu.board')}}
                            </a></li>
                    @endif

                    @if(Config::get('app.features.finances') || Config::get('app.features.projects') || Config::get('app.features.customers'))
                        <li class="treeview" id="scrollspy-components">
                            <a href="javascript::;"><i
                                        class="fa fa-chevron-circle-down"></i> {{trans('application.main_menu.office')}}
                            </a>
                            <ul class="nav treeview-menu">
                                @if(Config::get('app.features.customers') && (Auth::user()->canManage() && !isset($customer) || !isset($customer) && Config::get('app.features.customers') && Auth::user()->isAdmin()))
                                    <li>
                                        <a href="{{ url('/office/customer') }}">{{trans('application.main_menu.customers')}}</a>
                                    </li>
                                @elseif(Config::get('app.features.customers') && (Auth::user()->canManage() && isset($customer) || isset($customer) && Config::get('app.features.customers') && Auth::user()->isAdmin()))
                                    <li class="treeview" id="scrollspy-components">
                                        <a href="{{ url('/office/finance') }}">{{trans('application.main_menu.customer')}}
                                            <i class="fa fa-angle-left pull-right"></i></a>
                                        <ul class="nav treeview-menu active">
                                            <li>
                                                <a href="{{ url('/office/customer') }}">{{trans('application.main_menu.customer_search')}}</a>
                                            </li>
                                            <li>
                                                <a href="{{ url("/office/customer/".$customer->id) }}">{{trans('office.customer.side_menu.customer_details')}}</a>
                                            </li>
                                            <li>
                                                <a href="{{ url("/office/customer/questionnaries/".$customer->id) }}">{{trans('office.customer.side_menu.questionnaries')}}</a>
                                            </li>
                                            <li>
                                                <a class="new-quest">{{trans('office.customer.side_menu.new_quest')}}</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Config::get('app.features.projects') && !isset($project))
                                    <li>
                                        <a href="{{ url('/office/project') }}">{{trans('application.main_menu.projects')}}</a>
                                    </li>
                                @elseif(Config::get('app.features.projects') && isset($project))
                                    <li class="treeview" id="scrollspy-components">
                                        <a href="{{ url('/office/finance') }}">{{trans('application.main_menu.project')}}
                                            <i class="fa fa-angle-left pull-right"></i></a>
                                        <ul class="nav treeview-menu active">
                                            <li>
                                                <a href="{{ url("/office/project") }}">{{trans('application.main_menu.project_search')}}</a>
                                            </li>
                                            <li>
                                                <a href="{{ url("/office/project/".$project->id) }}">{{trans('office.project.side_menu.project_detials')}}</a>
                                            </li>
                                            <li>
                                                <a href="{{ url("/office/project/workstream/".$project->id) }}">{{trans('office.project.side_menu.workstream')}}</a>
                                            </li>
                                            <li>
                                                <a href="{{ url("/office/project/backlog/".$project->id) }}">{{trans('office.project.side_menu.project_backlog')}}</a>
                                            </li>
                                            <li>
                                                <a class="new-task">{{trans('office.project.side_menu.new_task_note')}}</a>
                                            </li>
                                            <li>
                                                <a href="{{ url("/office/project/files/".$project->id) }}">{{trans('office.project.side_menu.file_share')}}</a>
                                            </li>
                                            <li>
                                                <a href="{{ url("/office/project/requirements/".$project->id) }}">{{trans('office.project.side_menu.requirements')}}</a>
                                            </li>
                                            <li>
                                                <a class="new-requirement">{{trans('office.project.side_menu.new_requirement')}}</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Config::get('app.features.finances') && (Auth::user()->canManage() || Auth::user()->isAdmin()))
                                    <li>
                                        <a href="{{ url('/office/finance') }}">{{trans('application.main_menu.finance')}}
                                            <i class="fa fa-angle-left pull-right"></i></a>
                                        <ul class="nav treeview-menu">
                                            <li>
                                                <a href="#">Invoices <i class="fa fa-angle-left pull-right"></i></a>
                                                <ul class="nav treeview-menu">
                                                    <li>
                                                        <a href="{{ url('/office/finance/invoices') }}">{{trans('finance.side_menu.invoices')}}</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url("/office/finance/invoices/drafts") }}">{{trans('finance.side_menu.draft_invoices')}}</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url("/office/finance/archive") }}">{{trans('finance.side_menu.invoice_archive')}}</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url("/office/finance/invoice?event=new_draft") }}">{{trans('finance.side_menu.new_invoice')}}</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">Subscriptions <i
                                                            class="fa fa-angle-left pull-right"></i></a>
                                                <ul class="nav treeview-menu">
                                                    <li>
                                                        <a href="{{ url("/office/finance/subscriptions") }}">{{trans('finance.side_menu.subscriptions')}}</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url("/office/finance/subscription?event=new_draft&type=RECURRING") }}">{{trans('finance.side_menu.new_subscription')}}</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">Quotes <i class="fa fa-angle-left pull-right"></i></a>
                                                <ul class="nav treeview-menu">
                                                    <li>
                                                        <a href="{{ url("/office/finance/quotes") }}">{{trans('finance.side_menu.quotes')}}</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url("/office/finance/quote?event=new_draft&type=QUOTE") }}">{{trans('finance.side_menu.new_quote')}}</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">Expenses <i class="fa fa-angle-left pull-right"></i></a>
                                                <ul class="nav treeview-menu">
                                                    <li>
                                                        <a href="{{ url("/office/finance/expenses") }}">{{trans('finance.side_menu.expenses')}}</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url("/office/finance/expense?event=new_draft&type=EXPENSE") }}">{{trans('finance.side_menu.new_expense')}}</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(Config::get('app.features.reports') && (Auth::user()->canManage() || Auth::user()->isAdmin()))
                        <li class="treeview" id="scrollspy-components">
                            <a href="javascript::;"><i
                                        class="fa fa-chevron-circle-down"></i> {{trans('application.main_menu.reports')}}
                            </a>
                            <ul class="nav treeview-menu">
                                <li>
                                    <a href="{{ url('/office/finance/invoices') }}">{{trans('application.main_menu.finance')}}</a>
                                </li>
                            </ul>
                        </li>
                    @endif


                    @if((Auth::user()->canManage() || Auth::user()->isAdmin()))
                        <li class="treeview" id="scrollspy-components">
                            <a href="javascript::;"><i
                                        class="fa fa-chevron-circle-down"></i> {{trans('application.main_menu.settings')}}
                            </a>
                            <ul class="nav treeview-menu">
                                <li>
                                    <a href="{{ url("/settings/profile") }}">{{trans('settings.left_menu.user_profile')}}</a>
                                </li>
                                <li><a href="{{ url("/settings") }}">{{trans('settings.left_menu.general')}}</a></li>
                                <li>
                                    <a href="#">Finance <i class="fa fa-angle-left pull-right"></i></a>
                                    <ul class="nav treeview-menu">
                                        <li>
                                            <a href="{{ url("/settings/finance") }}">{{trans('settings.left_menu.finance')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{ url("/settings/finance/category") }}">{{trans('settings.left_menu.expense_category')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{ url("/settings/new-category") }}">{{trans('settings.left_menu.new_category')}}</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">Users <i class="fa fa-angle-left pull-right"></i></a>
                                    <ul class="nav treeview-menu">
                                        <li>
                                            <a href="{{ url("/settings/manage-users") }}">{{trans('settings.left_menu.manage_users')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{ url("/settings/new-user") }}">{{trans('settings.left_menu.new_user')}}</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">Kanban <i class="fa fa-angle-left pull-right"></i></a>
                                    <ul class="nav treeview-menu">
                                        <li>
                                            <a href="{{ url("/settings/manage-board-templates") }}">{{trans('settings.left_menu.manage_boards')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{ url("/settings/new-board-template") }}">{{trans('settings.left_menu.new_board')}}</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">Questionnaires <i class="fa fa-angle-left pull-right"></i></a>
                                    <ul class="nav treeview-menu">
                                        <li>
                                            <a href="{{ url("/settings/qunestionnaries") }}">{{trans('settings.left_menu.manage_questionnaries')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{ url("/settings/questionnarie/new") }}">{{trans('settings.left_menu.new_questionnaries')}}</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{ url("/settings/notifications") }}">{{trans('settings.left_menu.notifications')}}</a>
                                </li>
                            </ul>
                        </li>
                    @endif


                </ul>
            </div>
            <div class="slimScrollBar"
                 style="width: 3px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 568.555694618273px; background: rgba(0, 0, 0, 0.2);"></div>
            <div class="slimScrollRail"
                 style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div>
        </div>
        <!-- /.sidebar -->
    </aside>
</div>

@if(Config::get('app.warning.enabled'))
    <div class="responsive-version">
        <?php echo Config::get('app.warning.message'); ?>
    </div>
@endif
@if(Config::get('app.thankyou.enabled'))
    <div class="thank-you-message">
        <?php echo Config::get('app.thankyou.message'); ?>
    </div>
@endif