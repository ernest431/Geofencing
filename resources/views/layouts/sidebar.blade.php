<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header  align-items-center">
        <a class="navbar-brand" href="javascript:void(0)">
            <img src="{{ asset('assets/img/theme/Geofencing.png') }}" class="navbar-brand-img" alt="...">
        </a>
        </div>
        <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Nav items -->
            <ul class="navbar-nav">                
                <li class="nav-item">
                    <a class="nav-link {{ setActive('maps') }}" href="{{ url('/maps') }}">
                    <i class="ni ni-pin-3 text-primary"></i>
                    <span class="nav-link-text">Maps</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ setActive('pasien') }}" href="{{ url('/pasien') }}">
                    <i class="ni ni-single-02 text-yellow"></i>
                    <span class="nav-link-text">Pasien</span>
                    </a>
                </li>
            </ul>
        </div>
        </div>
    </div>
</nav>