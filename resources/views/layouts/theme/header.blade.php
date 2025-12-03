<div class="header-container fixed-top" style="box-shadow: 0 4px 20px rgba(0,0,0,0.08); backdrop-filter: blur(10px); background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,249,250,0.95) 100%);">
    <header class="header navbar navbar-expand-sm" style="padding: 12px 20px;">
        <ul class="navbar-item flex-row">
            <li class="nav-item theme-logo">
                <a href="{{url('home')}}" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 8px 12px; border-radius: 10px; box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <b style="font-size: 22px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; letter-spacing: 0.5px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Carnicería Franco</b>
                </a>
            </li>
        </ul>

        <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 10px 12px; border-radius: 10px; transition: all 0.3s ease; box-shadow: 0 3px 10px rgba(240, 147, 251, 0.3); border: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-list">
                <line x1="8" y1="6" x2="21" y2="6"></line>
                <line x1="8" y1="12" x2="21" y2="12"></line>
                <line x1="8" y1="18" x2="21" y2="18"></line>
                <line x1="3" y1="6" x2="3" y2="6"></line>
                <line x1="3" y1="12" x2="3" y2="12"></line>
                <line x1="3" y1="18" x2="3" y2="18"></line>
            </svg>
        </a>




        <ul class="navbar-item flex-row navbar-dropdown">
            <li class="nav-item dropdown user-profile-dropdown order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 10px 15px; border-radius: 50px; box-shadow: 0 3px 10px rgba(79, 172, 254, 0.3); transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;">
                    <div style="background: rgba(255,255,255,0.3); padding: 6px; border-radius: 50%; backdrop-filter: blur(5px);">
                        <i class="far fa-user" style="color: white; font-size: 16px;"></i>
                    </div>
                    <span style="color: white; font-weight: 600; font-size: 14px;">{{ Auth::user()->name ?? 'Usuario' }}</span>
                </a>
                <div class="dropdown-menu position-absolute animated fadeInUp"
                     style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden; margin-top: 10px;">
                    <div class="user-profile-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px;">
                        <div class="media mx-auto">
                            <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 50%; backdrop-filter: blur(10px); margin-right: 15px;">
                                <i class="far fa-user" style="color: white; font-size: 24px;"></i>
                            </div>
                            <div class="media-body">
                                <h5 style="color: white; font-weight: 700; margin-bottom: 5px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">{{ Auth::user()->name ?? 'Luis Fax' }}</h5>
                                <p style="color: rgba(255,255,255,0.9); margin-bottom: 0; font-size: 13px;">{{ Auth::user()->email ?? 'developer@example.com' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-item" style="padding: 0; margin: 8px;">
                        <a href="user_profile.html" style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 10px; transition: all 0.3s ease; text-decoration: none; background: transparent;">
                            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 8px; border-radius: 8px;">
                                <i class="fas fa-user" style="color: white; font-size: 14px;"></i>
                            </div>
                            <span style="color: #3B3F5C; font-weight: 600; font-size: 14px;">Mi Perfil</span>
                        </a>
                    </div>

                    <div style="border-top: 1px solid #f0f0f0; margin: 5px 15px;"></div>

                    <div class="dropdown-item" style="padding: 0; margin: 8px;">
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit()"
                            style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 10px; transition: all 0.3s ease; text-decoration: none; background: transparent;">
                            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 8px; border-radius: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                            </div>
                            <span style="color: #3B3F5C; font-weight: 600; font-size: 14px;">Cerrar Sesión</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>

<style>
/* Hover effects para header */
.sidebarCollapse:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(240, 147, 251, 0.5) !important;
}

.user-profile-dropdown .nav-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(79, 172, 254, 0.5) !important;
}

.dropdown-item a:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    transform: translateX(5px);
}

.theme-logo a:hover b {
    transform: scale(1.05);
    display: inline-block;
}

/* Animación del logo */
.theme-logo a div {
    transition: all 0.3s ease;
}

.theme-logo a:hover div {
    transform: rotate(5deg) scale(1.1);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.5);
}

/* Mejoras del dropdown */
.dropdown-menu {
    animation: slideDown 0.3s ease-in-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Efecto de pulso en el ícono de usuario */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.user-profile-dropdown .nav-link div {
    animation: pulse 2s infinite;
}
</style>
