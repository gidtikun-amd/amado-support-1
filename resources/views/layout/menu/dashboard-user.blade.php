{{-----------------------------------------------------------}}
{{--|   ROLE :            General User    |------------------}}
{{--|   HEADER MENU :     Dashboard       |------------------}}
{{-----------------------------------------------------------}}



{{---- Start ---------------------------------------------------------------------------------------------------------------}}

    {{-- Header --}}
    @if(session()->get('identity') == 'user')
    <li class="menu-header">Amado Support</li>
    @endif

    {{-- Menu : Dashboard --}}
    @if( $role->privilegeMenus('user.home') )
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link {{ request()->is('user') ? 'active' : '' }}" href="{{url('user')}}">
            <i class="fas fa-home"></i>
            <span class="nav-link-text ml-2">Dashboard</span>
        </a>
    </li>
    <li class="nav-item" data-toggle="tooltip" data-placement="right">
        <a class="nav-link {{ request()->is('user/asset/handle') ? 'active' : '' }}" href="{{url('user/asset/handle')}}">
            <i class="fas fa-tags"></i>
            <span class="nav-link-text ml-2">การถือครองทรัพย์สิน</span>
        </a>
    </li>
    @endif

{{---- End ---------------------------------------------------------------------------------------------------------------}}
