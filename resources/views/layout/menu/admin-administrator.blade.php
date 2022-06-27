{{-----------------------------------------------------------}}
{{--|   ROLE :            Administrator    |------------------}}
{{--|   HEADER MENU :     Administrator | Admin       |------------------}}
{{-----------------------------------------------------------}}



{{---- Start ---------------------------------------------------------------------------------------------------------------}}


    {{-- Header --}}
    @if( $role->privilegeMenus('user.admin.group') )
    <li class="menu-header text-amado">Administrator&nbsp;&nbsp;|&nbsp;&nbsp;Admin</li>

    {{-- Menu : อุปกรณ์สำนักงาน --}}
    @if( $role->privilegeMenus('user.ad.equ.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-a-admin-asset" data-parent="#menu-nav">
            <i class="fas fa-box"></i>
            <span class="nav-link-text ml-2">อุปกรณ์สำนักงาน</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('administrative/items/admin*') or request()->is('administrative/equipment-req/admin*') ) show @endif"  id="menu-a-admin-asset">
            @if( $role->privilegeMenus('user.ad.equ.item') )
                <li class="{{ request()->is('administrative/items/admin/management*') ? 'active' : '' }}">
                    <a href="{{url('/administrative/items/admin/management')}}">จัดการอุปกรณ์สำนักงาน</a>
                </li>
            @endif
            @if( $role->privilegeMenus('user.ad.equ.req') )
                <li class="{{ request()->is('administrative/equipment-req/admin/management*') ? 'active' : '' }}">
                    <a href="{{url('/administrative/equipment-req/admin/management')}}">รายการคำขอเบิกอุปกรณ์สำนักงาน</a>
                </li>
            @endif
        </ul>
    </li>
    @endif

    {{-- Menu : Job Request --}}
    @if( $role->privilegeMenus('user.ad.equ.job.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-a-admin-jobreq" data-parent="#menu-nav">
            <i class="fas fa-file-signature"></i>
            <span class="nav-link-text ml-2">Job Request</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('administrative/job-req/admin*')) show @endif"  id="menu-a-admin-jobreq">
            @if( $role->privilegeMenus('user.ad.equ.job.list') )
                <li class="{{ request()->is('administrative/job-req/admin/management*') ? 'active' : '' }}">
                    <a href="{{url('/administrative/job-req/admin/management')}}">รายการคำขอ Job Request</a>
                </li>
            @endif
        </ul>
    </li>
    @endif

    {{-- Menu : รายงาน --}}
    @if( $role->privilegeMenus('user.ad.report.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-a-adm-report" data-parent="#menu-nav">
            <i class="fas fa-clipboard"></i>
            <span class="nav-link-text ml-2">รายงาน</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('administrative/report*')) show @endif" id="menu-a-adm-report">
            @if( $role->privilegeMenus('user.ad.report.sbger') )
            <li class="{{ request()->is('administrative/report/summary-budget-eqreq*') ? 'active' : '' }}">
                <a href="{{url('/administrative/report/summary-budget-eqreq')}}">รายงานสรุป Budget การเบิกอุปกรณ์สำนักงาน</a>
            </li>
            @endif
            @if( $role->privilegeMenus('user.ad.report.ser') )
            <li class="{{ request()->is('administrative/report/summary-eqreq*') ? 'active' : '' }}">
                <a href="{{url('/administrative/report/summary-eqreq')}}">รายงานสรุปการเบิกอุปกรณ์สำนักงาน</a>
            </li>
            @endif
        </ul>
    </li>
    @endif

    <li><hr class="hr-nav-administrator"></li>
    @endif

{{---- End ---------------------------------------------------------------------------------------------------------------}}
