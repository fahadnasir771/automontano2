@if ($configData['mainLayoutType'] == 'horizontal')
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu {{ $configData['navbarColor'] }} navbar-fixed">
        <div class="navbar-header d-xl-block d-none">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item"><a class="navbar-brand" href="dashboard-analytics">
                        <div class="brand-logo"></div>
                    </a></li>
            </ul>
        </div>
    @else
        <nav
            class="header-navbar navbar-expand-lg navbar navbar-with-menu {{ $configData['navbarClass'] }} navbar-light navbar-shadow {{ $configData['navbarColor'] }}">
@endif
<div class="navbar-wrapper">
    <div class="navbar-container content">
        <div class="navbar-collapse" id="navbar-mobile">
            <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">

            </div>
            <ul class="nav navbar-nav float-right">
                {{-- <li class="dropdown dropdown-language nav-item"><a class="dropdown-toggle nav-link" id="dropdown-flag"
                        href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                            class="flag-icon flag-icon-us"></i><span class="selected-language">English</span></a>
                    <div class="dropdown-menu" aria-labelledby="dropdown-flag"><a class="dropdown-item" href="#"
                            data-language="en"><i class="flag-icon flag-icon-us"></i> English</a><a
                            class="dropdown-item" href="#" data-language="fr"><i class="flag-icon flag-icon-fr"></i>
                            French</a><a class="dropdown-item" href="#" data-language="de"><i
                                class="flag-icon flag-icon-de"></i> German</a><a class="dropdown-item" href="#"
                            data-language="pt"><i class="flag-icon flag-icon-pt"></i> Portuguese</a></div>
                </li> --}}
                {{-- <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i
                            class="ficon feather icon-maximize"></i></a></li> --}}

                {{-- <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#"
                        data-toggle="dropdown"><i class="ficon feather icon-bell"></i><span
                            class="badge badge-pill badge-primary badge-up">5</span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header m-0 p-2">
                                <h3 class="white">5 New</h3><span class="grey darken-2">App Notifications</span>
                            </div>
                        </li>
                        <li class="scrollable-container media-list">
                            <a class="d-flex justify-content-between" href="javascript:void(0)">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left"><i
                                            class="feather icon-plus-square font-medium-5 primary"></i></div>
                                    <div class="media-body">
                                        <h6 class="primary media-heading">You have new order!</h6><small
                                            class="notification-text"> Are your going to meet me tonight?</small>
                                    </div><small>
                                        <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">9 hours
                                            ago</time></small>
                                </div>
                            </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left"><i
                                            class="feather icon-download-cloud font-medium-5 success"></i></div>
                                    <div class="media-body">
                                        <h6 class="success media-heading red darken-1">99% Server load</h6><small
                                            class="notification-text">You got new order of goods.</small>
                                    </div><small>
                                        <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">5 hour
                                            ago</time></small>
                                </div>
                            </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left"><i
                                            class="feather icon-alert-triangle font-medium-5 danger"></i></div>
                                    <div class="media-body">
                                        <h6 class="danger media-heading yellow darken-3">Warning notifixation</h6><small
                                            class="notification-text">Server have 99% CPU usage.</small>
                                    </div><small>
                                        <time class="media-meta"
                                            datetime="2015-06-11T18:29:20+08:00">Today</time></small>
                                </div>
                            </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left"><i class="feather icon-check-circle font-medium-5 info"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="info media-heading">Complete the task</h6><small
                                            class="notification-text">Cake sesame snaps cupcake</small>
                                    </div><small>
                                        <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">Last
                                            week</time></small>
                                </div>
                            </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left"><i class="feather icon-file font-medium-5 warning"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="warning media-heading">Generate monthly report</h6><small
                                            class="notification-text">Chocolate cake oat cake tiramisu marzipan</small>
                                    </div><small>
                                        <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">Last
                                            month</time></small>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center"
                                href="javascript:void(0)">Read all notifications</a></li>
                    </ul>
                </li> --}}
                <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link"
                        href="#" data-toggle="dropdown">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name text-bold-600">
                            {{ isset(Auth::user()->name) ? Auth::user()->name : 'Demo' }}</span><span class="user-status">
                            <?php 

                                if(isset(Auth::user()->name)){
                                    $roles = [1 => 'Admin', 2 => 'Acceptor', 3 => 'Operator', 4 => 'Customer'];
                                    echo $roles[Auth::user()->role]; 
                                }else {
                                    echo 'Role';
                                }
                                
                                
                            ?>
                        </span></div><span><img class="round"
                                src="{{ asset('images/portrait/small/avatar-s-11.jpg') }}" alt="avatar" height="40"
                                width="40" /></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="page-user-profile"><i
                                class="feather icon-user"></i> Edit Profile</a>
                        <div class="dropdown-divider"></div>
                    
                        <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item"><i class="feather icon-power"></i> Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" >
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
</nav>

<!-- END: Header-->
