<header class="header-area" id="header-area">
    <div class="top-header">
        <div class="cust-pad">
            <div class="row top-header-content align-items-center">
                <div class="col-md-4 col-4">
                    <button class="resmenu-btn"><i class="ti-align-left"></i></button>
                    <div class="header-left d-flex align-items-center">
                        <div class="display-fullscreen">
                            <button  onclick="openFullscreen();">
                            <i class="ti-fullscreen"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-4">

                    <!--<div class="site-logo"><a class="navbar-brand" href="index.php"><img src="images/favicon.png" class="img-fluid" alt="Img" /></a></div>-->
                </div>
                <div class="col-md-4 col-4">

                    <div class="header-right d-flex align-items-center">
                        <div class="header-notifications ">
                            <span style="color: black">
                                {{__("pos::cashier.home.total_money")}}
                            </span>
                            <span class="badge badge-info"  v-text="totalDisplay">0</span>
                        </div>
                        {{-- <div class="header-notifications">
                            <button class="dropdown-toggle" type="button" id="notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="lnr lnr-alarm"></i>
                                <span class="not-counter"></span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="notifications">
                                <div class="notificaions ">
                                    <span>
                                        <i class="fa fa-money-bill " aria-hidden="true"></i>
                                    </span>
                                    <span class="badge badge-info" >@{{totalDisplay}}</span>

                                </div>
                                <hr/>
                                <div class="no-notificaions">
                                    <i class="lnr lnr-smile"></i>
                                    <p>Nothing to care about !</p>
                                </div>
                            </div>
                        </div> --}}
                        <div class="dropdown">
                            <button class="user-dropmenu dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="u-img"><img src="{{url(auth()->user()->image)}}" class="img-fluid" alt=""/></span>
                                <span> {{ auth()->user()->name }}</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" data-toggle="modal" data-target="#edit-profile" href="javascript;;"><i class="lnr lnr-user"></i>
                                    {{__("pos::cashier.home.Edit Profile")}}
                                </a>
                                <a class="dropdown-item" href="#"
                                     onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                     ><i class="lnr lnr-power-switch"></i>{{ __('apps::dashboard.navbar.logout') }}</a>

                                <form id="logout-form" action="{{ route('cashier.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
