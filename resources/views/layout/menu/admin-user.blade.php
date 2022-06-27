{{-----------------------------------------------------------}}
{{--|   ROLE :          General User        |------------------}}
{{--|   HEADER MENU :   User | ส่วนงาน Admin    |------------------}}
{{-----------------------------------------------------------}}



{{---- Start ---------------------------------------------------------------------------------------------------------------}}

    {{-- Header --}}
    @if(session()->get('identity') == 'user')
    <li class="menu-header">User&nbsp;&nbsp;|&nbsp;&nbsp;ส่วนงาน Admin</li>
    @endif

    {{-- Menu : เบิกอุปกรณ์สำนักงาน --}}
    @if( $role->privilegeMenus('user.ad.equreq.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-nav-admin-asset" data-parent="#menu-nav">
            <i class="fas fa-box"></i>
            <span class="nav-link-text ml-2">เบิกอุปกรณ์สำนักงาน</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('administrative/equipment-req/user*') ) show @endif"  id="menu-nav-admin-asset">
            @if( $role->privilegeMenus('user.ad.equreq.create') )
                <li class="{{ request()->is('administrative/equipment-req/user/create*') ? 'active' : '' }}">
                    <a href="{{url('administrative/equipment-req/user/create')}}">สร้างคำขอเบิกอุปกรณ์สำนักงาน</a>
                </li>
            @endif
            @if( $role->privilegeMenus('user.ad.equreq.history') )
                <li class="{{ request()->is('administrative/equipment-req/user/histor*') ? 'active' : '' }}">
                    <a href="{{url('administrative/equipment-req/user/history')}}">ประวัติคำขอเบิกอุปกรณ์สำนักงาน</a>
                </li>
            @endif
        </ul>
    </li>
    @endif

    {{-- Menu : Job Request --}}
    @if( $role->privilegeMenus('user.ad.jobreq.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-nav-admin-jr" data-parent="#menu-nav">
            <i class="fas fa-file-signature"></i>
            <span class="nav-link-text ml-2">Job Request</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('administrative/job-req/user*') ) show @endif"  id="menu-nav-admin-jr">
            @if( $role->privilegeMenus('user.ad.jobreq.create') )
                <li class="{{ request()->is('administrative/job-req/user/create*') ? 'active' : '' }}">
                    <a href="{{url('administrative/job-req/user/create')}}">สร้างคำขอ Job Request</a>
                </li>
            @endif
            @if( $role->privilegeMenus('user.ad.jobreq.history') )
                <li class="{{ request()->is('administrative/job-req/user/histor*') ? 'active' : '' }}">
                    <a href="{{url('administrative/job-req/user/history')}}">ประวัติคำขอ Job Request</a>
                </li>
            @endif
        </ul>
    </li>
    @endif

{{---- End ---------------------------------------------------------------------------------------------------------------}}
