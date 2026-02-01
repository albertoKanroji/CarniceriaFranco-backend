@php
    use App\Config\MenuConfig;
    $menuItems = MenuConfig::getMenuItems();
@endphp

<div class="sidebar-wrapper sidebar-theme" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 2px 0 10px rgba(0,0,0,0.1);">
    <nav id="compactSidebar" class="sliderbar">
        <ul class="menu-categories sliderbar">
            @foreach($menuItems as $menu)
                <li class="menu active" style="margin-bottom: 8px; border-radius: 12px; transition: all 0.3s ease;">
                    <a href="#{{ $menu['id'] }}" data-active="true" class="menu-toggle" style="border-radius: 12px; padding: 12px 15px;">
                        <div class="base-menu" bis_skin_checked="1">
                            <div class="base-icons" bis_skin_checked="1" style="background: rgba(255,255,255,0.15); padding: 10px; border-radius: 10px; backdrop-filter: blur(10px);">
                                {!! MenuConfig::renderIcon($menu['icon']) !!}
                            </div>
                            <span style="font-weight: 600; letter-spacing: 0.5px; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{ $menu['title'] }}</span>
                        </div>
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-chevron-left" style="opacity: 0.8;">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </li>
            @endforeach
        </ul>
    </nav>

    <div id="compact_submenuSidebar" class="submenu-sidebar ps" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); box-shadow: 2px 0 15px rgba(0,0,0,0.08);">
        @foreach($menuItems as $menu)
            <div class="submenu {{ $loop->first ? 'show' : '' }}" id="{{ $menu['id'] }}" bis_skin_checked="1" style="padding: 15px;">
                <ul class="submenu-list" data-parent-element="#{{ $menu['id'] }}">
                    @foreach($menu['submenu'] as $submenu)
                        <li class="active" style="margin-bottom: 6px; border-radius: 10px; transition: all 0.3s ease; overflow: hidden;">
                            <a href="{{ url($submenu['url']) }}" class="menu-toggle" data-active="true"
                               style="background: {{ $submenu['gradient'] }}; border-radius: 10px; padding: 12px 16px; box-shadow: 0 2px 8px {{ $submenu['shadow'] }}; transition: all 0.3s ease;">
                                <div class="base-menu">
                                    <div class="base-icons" style="background: rgba(255,255,255,0.2); padding: 8px; border-radius: 8px; margin-right: 12px; backdrop-filter: blur(5px);">
                                        {!! MenuConfig::renderSubmenuIcon($submenu['icon']) !!}
                                    </div>
                                    <span style="font-weight: 600; letter-spacing: 0.3px; color: white; text-shadow: 0 1px 3px rgba(0,0,0,0.2);">{{ $submenu['title'] }}</span>
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
/* Efectos hover modernos */
.sidebar-wrapper .menu-categories li:hover {
    transform: translateX(5px);
    background: rgba(255,255,255,0.1);
}

.submenu-list li a:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
}

.base-icons {
    transition: all 0.3s ease;
}

.submenu-list li a:hover .base-icons {
    transform: rotate(5deg) scale(1.1);
}

/* Animación suave */
.submenu-list li {
    animation: fadeInUp 0.3s ease-in-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Scroll personalizado */
.submenu-sidebar::-webkit-scrollbar {
    width: 6px;
}

.submenu-sidebar::-webkit-scrollbar-track {
    background: rgba(0,0,0,0.05);
    border-radius: 10px;
}

.submenu-sidebar::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

.submenu-sidebar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}
</style>
