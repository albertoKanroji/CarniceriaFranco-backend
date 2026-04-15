<div class="header-container fixed-top cf-header-shell">
    <header class="header navbar navbar-expand-sm" id="header">
        <ul class="navbar-item flex-row cf-header-left">
            <li class="nav-item theme-logo cf-theme-logo">
                <a href="{{ url('home') }}" class="cf-logo-link" aria-label="Ir al inicio">
                    <span class="cf-logo-mark">
                        <i class="fas fa-store"></i>
                    </span>
                </a>

                <a href="javascript:void(0);" class="sidebarCollapse cf-menu-trigger" data-placement="bottom" aria-label="Abrir menu lateral">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-list">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3" y2="6"></line>
                        <line x1="3" y1="12" x2="3" y2="12"></line>
                        <line x1="3" y1="18" x2="3" y2="18"></line>
                    </svg>
                </a>

                <span class="cf-company-text">CARNICERIA FRANCO</span>

                <div wire:offline class="alert alert-danger text-center cf-offline-alert mb-0">
                    <strong>Sin conexion.</strong>
                </div>
            </li>
        </ul>

        <ul class="navbar-item flex-row navbar-dropdown cf-user-nav">
            <li class="nav-item dropdown user-profile-dropdown order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user cf-user-pill"
                    id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-user"></i>
                    <span class="cf-user-name">{{ Auth::user()->name ?? 'Usuario' }}</span>
                </a>

                <div class="dropdown-menu position-absolute animated fadeInUp cf-user-dropdown" aria-labelledby="userProfileDropdown">
                    <div class="cf-user-top">
                        <h6>{{ Auth::user()->name ?? 'Usuario' }}</h6>
                        <p>{{ Auth::user()->profile ?? 'Perfil' }}</p>
                    </div>

                    <a href="user_profile.html" class="dropdown-item cf-dropdown-link">
                        <i class="fas fa-user"></i>
                        <span>Mi Perfil</span>
                    </a>

                    <div class="dropdown-item p-0">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="cf-logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Cerrar sesion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>

<style>
.cf-header-shell {
    z-index: 1100;
    background: #f8fafc;
    border-bottom: 1px solid #dbe1e8;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.07);
}

#header {
    min-height: 72px;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: transparent;
}

.cf-header-left {
    margin: 0;
    min-width: 0;
}

.cf-theme-logo {
    margin: 0 !important;
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.cf-logo-link {
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}

.cf-logo-mark {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #1f2937;
    color: #f8fafc;
}

.cf-menu-trigger {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #111827 !important;
    background: #eef2f7;
    border: 1px solid #d1d9e2;
    transition: all .2s ease;
}

.cf-menu-trigger:hover {
    background: #e2e8f0;
}

.cf-company-text {
    color: #111827;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 0.2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 62vw;
}

.cf-offline-alert {
    margin-left: 8px;
    padding: 3px 8px;
    font-size: 11px;
    border-radius: 6px;
}

.cf-user-nav {
    margin: 0;
}

.cf-user-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #111827 !important;
    padding: 6px 10px;
    border-radius: 999px;
    border: 1px solid #d1d5db;
    background: #ffffff;
}

.cf-user-name {
    font-size: 13px;
    font-weight: 600;
    max-width: 130px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cf-user-dropdown {
    min-width: 240px;
    margin-top: 10px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(15, 23, 42, 0.1);
    padding: 6px;
}

.cf-user-top {
    padding: 10px 12px;
    border-bottom: 1px solid #eceff3;
    margin-bottom: 4px;
}

.cf-user-top h6 {
    margin: 0;
    color: #111827;
    font-size: 14px;
    font-weight: 700;
}

.cf-user-top p {
    margin: 2px 0 0;
    color: #6b7280;
    font-size: 12px;
}

.cf-dropdown-link {
    display: flex;
    align-items: center;
    gap: 8px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

.cf-dropdown-link:hover {
    background: #f3f4f6;
}

.cf-logout-btn {
    width: 100%;
    border: 0;
    background: transparent;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    color: #b91c1c;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.cf-logout-btn:hover {
    background: #fef2f2;
}

@media (max-width: 991px) {
    #header {
        min-height: 68px;
        padding: 8px 10px;
    }

    .cf-company-text {
        font-size: 15px;
        max-width: 46vw;
    }

    .cf-user-name {
        max-width: 85px;
    }
}

@media (max-width: 576px) {
    .cf-company-text {
        font-size: 14px;
        max-width: 38vw;
    }

    .cf-user-name {
        display: none;
    }

    .cf-user-pill {
        padding: 6px 8px;
    }

    .cf-offline-alert {
        display: none;
    }
}
</style>
