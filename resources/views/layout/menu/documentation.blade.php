{{-----------------------------------------------------------}}
{{--|   ROLE :          All             |------------------}}
{{--|   HEADER MENU :   Documentation   |------------------}}
{{-----------------------------------------------------------}}



{{---- Start ---------------------------------------------------------------------------------------------------------------}}

    {{-- Header --}}
    @if(session()->get('identity') == 'user')
    <li class="menu-header">Documentation</li>

    {{-- Menu : วิธีการใช้งาน --}}
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link" href="/files/user_manual.pdf" target="_blank">
            <i class="fas fa-book"></i>
            <span class="nav-link-text ml-2">วิธีการใช้งาน</span>
        </a>
    </li>
    @endif

{{---- End ---------------------------------------------------------------------------------------------------------------}}
