{{-----------------------------------------------------------}}
{{--|   ROLE :          General User        |------------------}}
{{--|   HEADER MENU :   User | ส่วนงาน MIS    |------------------}}
{{-----------------------------------------------------------}}



{{---- Start ---------------------------------------------------------------------------------------------------------------}}

    {{-- Header --}}
    @if(session()->get('identity') == 'user')
    <li class="menu-header">User&nbsp;&nbsp;|&nbsp;&nbsp;ส่วนงาน MIS</li>
    @endif

    {{-- Menu : Job Request --}}
    @if( $role->privilegeMenus('user.jobreq.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-nav-jobreq-u" data-parent="#menu-nav">
            <i class="fas fa-file-signature"></i>
            <span class="nav-link-text ml-2">Job Request</span>
        </a>
        <ul class="sidenav-second-level collapse
        @if (request()->is('user/send_jobrequest*') or request()->is('user/history_jobrequest*') or request()->is('user/it-jobrequest*') ) show @endif"
        id="menu-nav-jobreq-u">
            @if( $role->privilegeMenus('user.jobreq.create') )
                <li class="{{ request()->is('user/send_jobrequest*') ? 'active' : '' }}"><a href="{{url('user/send_jobrequest')}}">ส่งคำขอ Job Request</a></li>
            @endif
            @if( $role->privilegeMenus('user.jobreq.history') )
                <li class="{{ request()->is('user/history_jobrequest*') ? 'active' : '' }}"><a href="{{url('user/history_jobrequest')}}">ประวัติคำขอ Job Request</a></li>
            @endif
                <li class="{{ request()->is('user/it-jobrequest*') ? 'active' : '' }}"><a href="{{url('user/it-jobrequest/survey')}}">การประเมินการใช้งานระบบ</a></li>
        </ul>
    </li>
    @endif

    {{-- Menu : Item Request --}}
    @if( $role->privilegeMenus('user.itmreq.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-nav-itemreq-u" data-parent="#menu-nav">
            <i class="fas fa-file-signature"></i>
            <span class="nav-link-text ml-2">Item Request</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('user/send_itemrequest*') or request()->is('user/history_itemrequest*') or request()->is('user/approve_itemrequest*') ) show @endif" id="menu-nav-itemreq-u">
            @if( $role->privilegeMenus('user.itmreq.create') )
                <li class="{{ request()->is('user/send_itemrequest') ? 'active' : '' }}"><a href="{{url('user/send_itemrequest')}}">ส่งคำขอ Item Request</a></li>
            @endif
            {{-- @if( $role->privilegeMenus('user.crm.itmreq.create') )
                <li class="{{ request()->is('user/send_itemrequest_crm') ? 'active' : '' }}"><a href="{{url('user/send_itemrequest_crm')}}">ส่งคำขอ CRM Item Request</a></li>
            @endif --}}
            @if( $role->privilegeMenus('user.itmreq.history') )
                <li class="{{ request()->is('user/history_itemrequest') ? 'active' : '' }}"><a href="{{url('user/history_itemrequest')}}">ประวัติคำขอ Item Request</a></li>
            @endif
            {{-- @if( $role->privilegeMenus('user.crm.itmreq.history') )
                <li class="{{ request()->is('user/history_itemrequest_crm') ? 'active' : '' }}"><a href="{{url('user/history_itemrequest_crm')}}">ประวัติคำขอ CRM Item Request</a></li>
            @endif --}}
            {{-- @if( $role->privilegeMenus('user.itmreq.approve') )
            <li class="{{ request()->is('user/approve_itemrequest*') ? 'active' : '' }}"><a href="{{url('user/approve_itemrequest')}}">Approve Item Request</a></li>
            @endif --}}
        </ul>
    </li>
    @endif

    {{-- Menu : Ticket --}}
    @if( $role->privilegeMenus('user.ticket.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-nav-ticket-u" data-parent="#menu-nav">
            <i class="fas fa-ticket-alt"></i>
            <span class="nav-link-text ml-2">Ticket</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('user/ticket*') ) show @endif" id="menu-nav-ticket-u">
            @if( $role->privilegeMenus('user.ticket.create') )
                <li class="{{ request()->is('user/ticket/create*') ? 'active' : '' }}"><a href="{{url('user/ticket/create')}}">สร้างคำขอ Ticket</a></li>
            @endif
            @if( $role->privilegeMenus('user.ticket.history') )
                <li class="{{ request()->is('user/ticket/history*') ? 'active' : '' }}"><a href="{{url('user/ticket/history')}}">ประวัติคำขอ Ticket</a></li>
            @endif
            @if( $role->privilegeMenus('user.ticket.saform') )
                <li class="{{ request()->is('user/ticket/survey*') ? 'active' : '' }}"><a href="{{url('user/ticket/survey')}}">แบบประเมินความพึงพอใจ</a></li>
            @endif
        </ul>
    </li>
    @endif

    {{-- Menu : IT Memo --}}
    @if( $role->privilegeMenus('user.itmemo.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-nav-memo-u" data-parent="#menu-nav">
            <i class="fas fa-ticket-alt"></i>
            <span class="nav-link-text ml-2">IT Memo</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('user/memo*') ) show @endif" id="menu-nav-memo-u">
            @if( $role->privilegeMenus('user.itmemo.create') )
                <li class="{{ request()->is('user/memo/create*') ? 'active' : '' }}"><a href="{{url('user/memo/create')}}">สร้างคำขอ Memo</a></li>
            @endif
            @if( $role->privilegeMenus('user.itmemo.history') )
                <li class="{{ request()->is('user/memo/history*') ? 'active' : '' }}"><a href="{{url('user/memo/history')}}">ประวัติคำขอ Memo</a></li>
            @endif
        </ul>
    </li>
    @endif

{{-- End --------------------------------------------------------------------------------------------}}
