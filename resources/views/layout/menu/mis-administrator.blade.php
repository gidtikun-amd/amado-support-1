{{-----------------------------------------------------------}}
{{--|   ROLE :          Administrator           |------------------}}
{{--|   HEADER MENU :   Administrator | MIS     |------------------}}
{{-----------------------------------------------------------}}



{{---- Start ---------------------------------------------------------------------------------------------------------------}}

    {{-- Header --}}
    @if(session()->get('identity') == 'admin')
    <li class="menu-header">Administrator&nbsp;&nbsp;|&nbsp;&nbsp;MIS</li>
    @endif

    {{-- Menu : หน้าหลัก --}}
    @if( $role->privilegeMenus('admin.it.home') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{url('admin')}}">
            <i class="fas fa-home"></i>
            <span class="nav-link-text ml-2">หน้าหลัก</span>
        </a>
    </li>
    @endif

    {{-- Menu : กำหนดสิทธิ์ผู้ใช้ --}}
    @if( $role->privilegeMenus('admin.role.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-a-mis-roleuser" data-parent="#menu-nav">
            <i class="fas fa-user-lock"></i>
            <span class="nav-link-text ml-2">การกำหนดตัวตนและสิทธิ์</span>
        </a>
        <ul class="sidenav-second-level collapse
                    @if (request()->is('admin/role*')) show @endif" id="menu-a-mis-roleuser">
            <li class="{{ request()->is('admin/role/pages-menus*') ? 'active' : '' }}"><a href="{{url('admin/role/pages-menus')}}">จัดการเพจและเมนู</a></li>
            <li class="{{ request()->is('admin/role/management*') ? 'active' : '' }}"><a href="{{url('admin/role/management')}}">การกำหนดสิทธิ์เข้าถึงการใช้งาน</a></li>
            <li class="{{ request()->is('admin/role/user*') ? 'active' : '' }}"><a href="{{url('admin/role/user')}}">กำหนดสิทธิ์ผู้ใช้</a></li>
        </ul>
    </li>
    @endif

    {{-- Menu : ข้อมูลหลัก --}}
    @if( $role->privilegeMenus('admin.maindata.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-a-mis-infosetup" data-parent="#menu-nav">
            <i class="fas fa-database"></i>
            <span class="nav-link-text ml-2">ข้อมูลหลัก</span>
        </a>

        <ul class="sidenav-second-level collapse
                    @if (request()->is('admin/location*') or request()->is('admin/type-asset*') or request()->is('admin/department*') or request()->is('admin/employee*')) show @endif"
            id="menu-a-mis-infosetup">
            @if( $role->privilegeMenus('admin.maindata.location') )
                <li class="{{ request()->is('admin/location*') ? 'active' : '' }}"><a href="{{url('admin/location')}}">จัดการข้อมูล Location</a></li>
            @endif
            @if( $role->privilegeMenus('admin.maindata.assettype') )
                <li class="{{ request()->is('admin/type-asset*') ? 'active' : '' }}"><a href="{{url('admin/type-asset')}}">จัดการข้อมูลประเภททรัพย์สิน</a></li>
            @endif
            @if( $role->privilegeMenus('admin.maindata.department') )
                <li class="{{ request()->is('admin/department*') ? 'active' : '' }}"><a href="{{url('admin/department')}}">จัดการข้อมูลฝ่าย</a></li>
            @endif
            @if( $role->privilegeMenus('admin.maindata.employee') )
                <li class="{{ request()->is('admin/employee*') ? 'active' : '' }}"><a href="{{url('admin/employee')}}">จัดการข้อมูลพนักงาน</a></li>
            @endif
        </ul>
    </li>
    @endif

    {{-- Menu : ทรัพย์สิน --}}
    @if( $role->privilegeMenus('admin.asset.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-a-mis-asset" data-parent="#menu-nav">
            <i class="fas fa-archive"></i>
            <span class="nav-link-text ml-2">ทรัพย์สิน</span>
        </a>
        <ul class="sidenav-second-level collapse
                    @if (request()->is('admin/asset*') || request()->is('admin/paper/asset') || request()->is('admin/sofware-license*')) show @endif"
            id="menu-a-mis-asset">
            @if( $role->privilegeMenus('admin.asset.manage') )
                <li class="{{ request()->is('admin/asset*') ? 'active' : '' }}"><a href="{{url('admin/asset')}}">จัดการข้อมูลทรัพย์สิน</a></li>
            @endif
            @if( $role->privilegeMenus('admin.asset.paper') )
                <li class="{{ request()->is('admin/paper/asset') ? 'active' : '' }}"><a href="{{url('admin/paper/asset')}}">ค้นหาเอกสารทรัพย์สิน</a></li>
            @endif
            @if( $role->privilegeMenus('admin.asset.swlicense') )
                <li class="{{ request()->is('admin/sofware-license*') ? 'active' : '' }}"><a href="{{url('admin/sofware-license')}}">Software License Control</a></li>
            @endif
        </ul>
    </li>
    @endif

    {{-- Menu : Job Request Form --}}
    @if( $role->privilegeMenus('admin.jobreq.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-a-mis-jobreq" data-parent="#menu-nav">
            <i class="fas fa-file-signature"></i>
            <span class="nav-link-text ml-2">Job Request</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('admin/jobrequest*')) show @endif" id="menu-a-mis-jobreq">
            @if( $role->privilegeMenus('admin.jobreq.list') )
                <li class="{{ request()->is('admin/jobrequest') ? 'active' : '' }}"><a href="{{url('admin/jobrequest')}}">รายการคำขอ Job Request</a></li>
                <li class="{{ request()->is('admin/jobrequest/dont-upload-files*') ? 'active' : '' }}">
                    <a href="{{url('admin/jobrequest/dont-upload-files')}}">รายการยังไม่ได้อัพโหลดเอกสาร</a>
                </li>
                <li class="{{ request()->is('admin/jobrequest/survey*') ? 'active' : '' }}">
                    <a href="{{url('admin/jobrequest/survey')}}">การประเมินการใช้งานระบบ</a>
                </li>
            @endif
        </ul>
    </li>
    @endif

    {{-- Menu : Item Request Form --}}
    @if( $role->privilegeMenus('admin.itmreq.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-a-mis-itemreq" data-parent="#menu-nav">
            <i class="fas fa-file-invoice"></i>
            <span class="nav-link-text ml-2">Item Request</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('admin/itemrequest*') || request()->is('admin/crm/item-request*')) show @endif" id="menu-a-mis-itemreq">
            @if( $role->privilegeMenus('admin.itmreq.list') )
                <li class="{{ request()->is('admin/itemrequest') ? 'active' : '' }}"><a href="{{url('admin/itemrequest')}}">รายการคำขอ Item Request</a></li>
                <li class="{{ request()->is('admin/itemrequest/dont-upload-files*') ? 'active' : '' }}">
                    <a href="{{url('admin/itemrequest/dont-upload-files')}}">รายการยังไม่ได้อัพโหลดเอกสาร</a>
                </li>
                {{-- <li class="{{ request()->is('admin/crm/item-request') ? 'active' : '' }}"><a href="{{url('admin/crm/item-request')}}">รายการคำขอ CRM Item Request</a></li> --}}
            @endif
        </ul>
    </li>
    @endif

    {{-- Menu : Ticket --}}
    @if( $role->privilegeMenus('admin.ticket.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-a-mis-ticket" data-parent="#menu-nav">
            <i class="fas fa-ticket-alt"></i>
            <span class="nav-link-text ml-2">Ticket</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('admin/ticket*')) show @endif" id="menu-a-mis-ticket">
            @if( $role->privilegeMenus('admin.ticket.subject') )
                <li class="{{ request()->is('admin/ticket/subject*') ? 'active' : '' }}"><a href="{{url('admin/ticket/subject')}}">จัดการเรื่องการแจ้ง Ticket</a></li>
            @endif
            @if( $role->privilegeMenus('admin.ticket.list') )
                <li class="{{ request()->is('admin/ticket/list*') ? 'active' : '' }}"><a href="{{url('admin/ticket/list')}}">รายการคำขอ Ticket</a></li>
            @endif
            @if( $role->privilegeMenus('admin.ticket.saform') )
                <li class="{{ request()->is('admin/ticket/survey*') ? 'active' : '' }}"><a href="{{url('admin/ticket/survey')}}">แบบประเมินความพึงพอใจ</a></li>
            @endif
        </ul>
    </li>
    @endif

    {{-- Menu : It Memo --}}
    @if( $role->privilegeMenus('admin.it.memo.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-a-mis-itmemo" data-parent="#menu-nav">
            <i class="fas fa-file-signature"></i>
            <span class="nav-link-text ml-2">IT Memo</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('admin/it-memo*')) show @endif" id="menu-a-mis-itmemo">
            @if( $role->privilegeMenus('admin.it.memo.list') )
                <li class="{{ request()->is('admin/it-memo/list*') ? 'active' : '' }}"><a href="{{url('admin/it-memo/list')}}">รายการคำขอ Memo</a></li>
                <li class="{{ request()->is('admin/it-memo/dont-upload-files*') ? 'active' : '' }}">
                    <a href="{{url('admin/it-memo/dont-upload-files')}}">รายการยังไม่ได้อัพโหลดเอกสาร</a>
                </li>
            @endif
        </ul>
    </li>
    @endif

     {{-- Menu : It External Problem --}}
     @if( $role->privilegeMenus('admin.it.memo.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link {{ request()->is('admin/it-external-problem/main') ? 'active' : '' }}" href="{{url('admin/it-external-problem/main')}}">
            <i class="fas fa-file-signature"></i>
            <span class="nav-link-text ml-2">บันทึกปัญหาภายนอก</span>
        </a>
    </li>
    @endif

    {{-- Menu : It System Status --}}
    @if( $role->privilegeMenus('admin.it.memo.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link {{ request()->is('admin/it-external-problem/system-status') ? 'active' : '' }}" href="{{url('admin/it-external-problem/system-status')}}">
            <i class="fas fa-file-signature"></i>
            <span class="nav-link-text ml-2">บันทึกสถานะระบบ</span>
        </a>
    </li>
    @endif

    {{-- Menu : รายงาน --}}
    @if( $role->privilegeMenus('admin.report.main') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#menu-a-mis-report" data-parent="#menu-nav">
            <i class="fas fa-clipboard"></i>
            <span class="nav-link-text ml-2">รายงาน</span>
        </a>
        <ul class="sidenav-second-level collapse @if (request()->is('admin/report*')) show @endif" id="menu-a-mis-report">            
            <li><a href="{{url('api/ad/report/employees')}}" target="_blank">รายชื่อพนักงาน</a></li>
            @if( $role->privilegeMenus('admin.report.exception') )
            <li class="{{ request()->is('admin/report/exception*') ? 'active' : '' }}"><a href="{{url('admin/report/exception')}}">รายการ Log Error</a></li>
            @endif
            @if( $role->privilegeMenus('admin.report.asset') )
                <li class="{{ request()->is('admin/report/asset*') ? 'active' : '' }}">
                    <a href="{{url('admin/report/asset')}}">รายงานทรัพย์สิน</a>
                </li>
                <li class="{{ request()->is('admin/report/license*') ? 'active' : '' }}">
                    {{-- api/report/it/license/export --}}
                    <a href="{{url('admin/report/license')}}">รายงานการถือครอง License</a>
                </li>
            @endif
            @if( $role->privilegeMenus('admin.report.summarysupport') )
                <li class="{{ request()->is('admin/report/summary-support*') ? 'active' : '' }}"><a href="{{url('admin/report/summary-support')}}">รายงานสรุปผลการ support</a></li>
            @endif
            @if( $role->privilegeMenus('admin.report.problemticket') )
                <li class="{{ request()->is('admin/report/problemticket*') ? 'active' : '' }}"><a href="{{url('admin/report/problemticket')}}">รายงานสรุปปัญหาที่เกิดขึ้น</a></li>
            @endif
            @if( $role->privilegeMenus('admin.report.problemtransferasset') )
                <li class="{{ request()->is('admin/report/transfer-asset*') ? 'active' : '' }}"><a href="{{url('admin/report/transfer-asset')}}">รายงานสรุปทรัพย์สินที่เกิดขึ้น</a></li>
            @endif
            @if( $role->privilegeMenus('admin.report.nonuppaperfiles') )
            <li class="{{ request()->is('admin/report/files-papers*') ? 'active' : '' }}"><a href="{{url('admin/report/files-papers')}}">รายงานเอกสารยังไม่ได้อัพโหลด</a></li>
            @endif
        </ul>
    </li>
    @endif

{{-- End --------------------------------------------------------------------------------------------}}
