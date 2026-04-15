@php
    use App\Config\MenuConfig;
    $menuItems = MenuConfig::getMenuItems();
@endphp

<div class="sidebar-wrapper sidebar-theme cf-sidebar-shell bg-white border-right">
    <nav id="compactSidebar" class="sliderbar bg-white border-right">
        <ul class="menu-categories sliderbar">
            @foreach($menuItems as $menu)
                <li class="menu mb-1">
                    <a href="#{{ $menu['id'] }}" data-active="true" class="menu-toggle d-flex flex-column align-items-center justify-content-center text-center rounded py-2 text-dark">
                        <div class="base-menu d-flex flex-column align-items-center w-100">
                            <div class="base-icons p-1 mb-2 text-secondary">
                                {!! MenuConfig::renderIcon($menu['icon']) !!}
                            </div>
                            <span class="small text-secondary px-1">{{ $menu['title'] }}</span>
                        </div>
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-chevron-left text-secondary">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </li>
            @endforeach
        </ul>
    </nav>

    <div id="compact_submenuSidebar" class="submenu-sidebar ps bg-white">
        @foreach($menuItems as $menu)
            <div class="submenu {{ $loop->first ? 'show' : '' }} p-3" id="{{ $menu['id'] }}">
                <ul class="submenu-list list-group list-group-flush" data-parent-element="#{{ $menu['id'] }}">
                    @foreach($menu['submenu'] as $submenu)
                        <li class="mb-1 rounded overflow-hidden list-group-item p-0 border-0 bg-transparent">
                            <a href="{{ url($submenu['url']) }}" data-active="true"
                               class="menu-toggle d-flex align-items-center rounded text-dark px-3 py-2 w-100 border border-light bg-light">
                                <div class="base-menu d-flex align-items-center w-100">
                                    <div class="base-icons p-1 mr-2 text-muted">
                                        {!! MenuConfig::renderSubmenuIcon($submenu['icon']) !!}
                                    </div>
                                    <span class="text-dark text-truncate">{{ $submenu['title'] }}</span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>

<style>
    .cf-sidebar-shell,
    .cf-sidebar-shell #compactSidebar,
    .cf-sidebar-shell #compact_submenuSidebar {
        background: #ffffff !important;
        box-shadow: none !important;
    }

    .cf-sidebar-shell #compactSidebar {
        border-right: 1px solid #e9ecef;
    }

    .cf-sidebar-shell .menu-categories li.menu {
        margin-left: 6px;
        margin-right: 6px;
        border-bottom: 1px solid #f1f3f5;
    }

    .cf-sidebar-shell .menu-categories li.menu > a,
    .cf-sidebar-shell .submenu-list li > a {
        box-shadow: none !important;
        transition: background-color .15s ease, color .15s ease;
    }

    .cf-sidebar-shell .menu-categories li.menu > a:hover,
    .cf-sidebar-shell .menu-categories li.menu.active > a,
    .cf-sidebar-shell .submenu-list li > a:hover,
    .cf-sidebar-shell .submenu-list li.active > a {
        background: #e9ecef !important;
        color: #212529 !important;
    }

    .cf-sidebar-shell .menu-categories .base-icons svg,
    .cf-sidebar-shell .submenu-list .base-icons svg {
        opacity: .85;
    }
</style>
