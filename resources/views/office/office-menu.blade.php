@if(!Auth::user()->isClient())
    <div style="width:100%;text-align:center;background-color:#DEE2E7   !important;"  id="top-menu-bar">
        <header class="main-header" style="max-width: 1100px;margin: 0 auto;text-align:left;background-color:#DEE2E7   !important;z-index:500;">
            <nav class="navbar navbar-static-top" role="navigation" style="background-color:#DEE2E7   !important;margin-left:0px;">
                 <!-- Sidebar toggle button-->

                @if((@$block == 'customer' || @$block == 'clean') && in_array($subblock, ['cdetails','quote']))
                    <div id="projects" style="display:none;">
                        <div style="float:left;padding-top:12px;width:90%;" id="board-search">
                            <select id="projects-list" style="width:100%;height:100px;">
                                <option></option>
                            </select>
                        </div>

                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav" style="font-size:15px;">
                                <li class="dropdown tasks-menu user user-menu" id="board-filter">
                                    <a href="#"  class="dropdown-toggle list-customers"  style="color:gray !important;width:43px;text-align:center;">
                                        <i class="ion-ios-people"></i></a>
                                </li>

                                <li class="dropdown messages-menu">
                                    <a href="#" class="dropdown-toggle"  style="color:gray !important;" id="add-new-project">
                                        <i class="ion-plus-circled"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div id="customers">
                        <div style="float:left;padding-top:12px;width:@if(is_object(@$customer)) 85% @else 90% @endif;" id="board-search">
                            <select id="customers-list" style="width:100%;height:100px;">
                                <option></option>
                            </select>
                        </div>

                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav" style="font-size:15px;">

                                <li class="dropdown messages-menu">
                                    <a href="#" class="dropdown-toggle"  style="color:gray !important;" id="add-new-customer">
                                        <i class="ion-android-person-add"></i></a>
                                </li>

                                @if(is_object(@$customer))
                                <li class="dropdown messages-menu">
                                    <a href="#" class="dropdown-toggle"  style="color:gray !important;" id="delete-customer">
                                        <i class="ion-trash-a"></i></a>
                                </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                @else
                    <div id="projects">
                        <div style="float:left;padding-top:12px;width:90%;" id="board-search">
                            <select id="projects-list" style="width:100%;height:100px;">
                                <option></option>
                            </select>
                        </div>

                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav" style="font-size:15px;">

                                <li class="dropdown messages-menu">
                                    <a href="#" class="dropdown-toggle"  style="color:gray !important;" id="add-new-project">
                                        <i class="ion-plus-circled"></i></a>
                                </li>


                                @if(is_object(@$project))
                                    <li class="dropdown messages-menu">
                                        <a href="#" class="dropdown-toggle"  style="color:gray !important;" id="delete-project">
                                            <i class="ion-trash-a"></i></a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div>

                    <div id="customers" style="display:none;">
                        <div style="float:left;padding-top:12px;width:@if(is_object(@$customer)) 85% @else 90% @endif;" id="board-search">
                            <select id="customers-list" style="width:100%;height:100px;">
                                <option></option>
                            </select>
                        </div>

                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav" style="font-size:15px;">
                                <li class="dropdown messages-menu">
                                    <a href="#" class="dropdown-toggle"  style="color:gray !important;" id="add-new-customer">
                                        <i class="ion-android-person-add"></i></a>
                                </li>

                                @if(is_object(@$customer))
                                    <li class="dropdown messages-menu">
                                        <a href="#" class="dropdown-toggle"  style="color:gray !important;" id="add-new-customer">
                                            <i class="ion-trash-a"></i></a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                @endif

            </nav>
        </header>
    </div>
@endif