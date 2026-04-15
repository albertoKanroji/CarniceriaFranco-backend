<div class="header-container fixed-top cf-header-shell">
    <header class="header navbar navbar-expand-sm cf-header-navbar">
        <div class="cf-header-left">
            <a href="{{ url('home') }}" class="cf-logo-link">
                <div class="cf-logo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <span class="cf-logo-text">Carniceria Franco</span>
            </a>
        </div>

        <div class="cf-header-right">
            <a href="javascript:void(0);" class="sidebarCollapse cf-menu-trigger" data-placement="bottom" aria-label="Abrir menu lateral">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3" y2="6"></line>
                    <line x1="3" y1="12" x2="3" y2="12"></line>
                    <line x1="3" y1="18" x2="3" y2="18"></line>
                </svg>
            </a>

            <ul class="navbar-item flex-row navbar-dropdown cf-user-nav">
                <li class="nav-item dropdown user-profile-dropdown order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user cf-user-pill" id="userProfileDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="cf-user-avatar">
                            <i class="far fa-user"></i>
                        </div>
                        <span class="cf-user-name">{{ Auth::user()->name ?? 'Usuario' }}</span>
                    </a>

                    <div class="dropdown-menu position-absolute animated fadeInUp cf-user-dropdown">
                        <div class="user-profile-section cf-user-top">
                            <div class="media mx-auto align-items-center">
                                <div class="cf-user-top-icon">
                                    <i class="far fa-user"></i>
                                </div>
                                <div class="media-body">
                                    <h5>{{ Auth::user()->name ?? 'Usuario' }}</h5>
                                    <p>{{ Auth::user()->email ?? 'developer@example.com' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-item cf-dropdown-item">
                            <a href="user_profile.html" class="cf-dropdown-link">
                                <div class="cf-dropdown-link-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span>Mi Perfil</span>
                            </a>
                        </div>

                        <div class="cf-dropdown-separator"></div>

                        <div class="dropdown-item cf-dropdown-item">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit()"
                                class="cf-dropdown-link">
                                <div class="cf-dropdown-link-icon danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-log-out">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                </div>
                                <span>Cerrar Sesion</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </header>
</div>

<style>
.cf-header-shell {
    z-index: 1100;
    background: linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(248,250,252,0.98) 100%);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(148, 163, 184, 0.18);
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
}

.cf-header-navbar {
    min-height: 72px;
    padding: 10px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
}

.cf-header-left,
.cf-header-right {
    display: flex;
    align-items: center;
}

.cf-header-right {
    margin-left: auto;
    gap: 10px;
}

.cf-logo-link {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    text-decoration: none;
}

.cf-logo-icon {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: 10px;
    padding: 8px 10px;
    box-shadow: 0 6px 12px rgba(79, 70, 229, 0.24);
}

.cf-logo-text {
    font-size: 26px;
    font-weight: 700;
    letter-spacing: 0.2px;
    line-height: 1;
    color: #3730a3;
}

.cf-menu-trigger {
    background: linear-gradient(135deg, #ec4899 0%, #f97316 100%);
    border-radius: 10px;
    padding: 8px 11px;
    box-shadow: 0 6px 12px rgba(249, 115, 22, 0.28);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: transform .2s ease, box-shadow .2s ease;
}

.cf-menu-trigger:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 16px rgba(249, 115, 22, 0.36);
}

.cf-user-nav {
    margin: 0;
}

.cf-user-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 11px;
    border-radius: 999px;
    background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%);
    box-shadow: 0 6px 12px rgba(2, 132, 199, 0.26);
}

.cf-user-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.24);
    color: #fff;
}

.cf-user-name {
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    max-width: 140px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cf-user-dropdown {
    min-width: 260px;
    margin-top: 10px;
    border: 0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 14px 30px rgba(15, 23, 42, 0.16);
}

.cf-user-top {
    padding: 14px;
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
}

.cf-user-top-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    margin-right: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.22);
    color: #fff;
    font-size: 18px;
}

.cf-user-top h5 {
    margin: 0;
    color: #fff;
    font-size: 15px;
    font-weight: 700;
}

.cf-user-top p {
    margin: 3px 0 0;
    color: rgba(255, 255, 255, 0.9);
    font-size: 12px;
}

.cf-dropdown-item {
    padding: 0;
    margin: 7px;
}

.cf-dropdown-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 11px;
    border-radius: 10px;
    text-decoration: none;
    color: #334155;
    font-weight: 600;
    transition: transform .2s ease, background-color .2s ease;
}

.cf-dropdown-link:hover {
    background: #f1f5f9;
    transform: translateX(3px);
}

.cf-dropdown-link-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #5965f3 0%, #6f4db8 100%);
    color: #fff;
}

.cf-dropdown-link-icon.danger {
    background: linear-gradient(135deg, #f472b6 0%, #fb7185 100%);
}

.cf-dropdown-separator {
    height: 1px;
    background: #e2e8f0;
    margin: 6px 14px;
}

@media (max-width: 991px) {
    .cf-header-navbar {
        padding: 9px 12px;
        min-height: 74px;
    }

    .cf-logo-text {
        font-size: 18px;
        max-width: 165px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .cf-user-name {
        max-width: 110px;
    }

    .cf-header-right {
        gap: 8px;
    }

    .cf-user-pill {
        padding: 7px 10px;
    }

    .cf-user-dropdown {
        right: 0;
        left: auto;
        min-width: 245px;
    }
}

@media (max-width: 576px) {
    .cf-header-navbar {
        min-height: 70px;
        padding: 8px 10px;
    }

    .cf-logo-text {
        font-size: 15px;
        max-width: 110px;
    }

    .cf-logo-icon {
        padding: 7px 9px;
    }

    .cf-user-pill {
        padding: 7px 9px;
    }

    .cf-user-name {
        max-width: 85px;
        font-size: 12px;
    }

    .cf-menu-trigger {
        padding: 7px 9px;
    }
}
</style>
