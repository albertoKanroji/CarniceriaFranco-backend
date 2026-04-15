<div class="header-container fixed-top border-bottom bg-white">
    <header class="header navbar navbar-expand navbar-light py-2" id="header">
        <div class="container-fluid px-2 px-md-3">
            <div class="d-flex align-items-center flex-grow-1">
                <a href="{{ url('home') }}" class="btn btn-sm btn-light border mr-2" aria-label="Ir al inicio">
                    <i class="fas fa-store"></i>
                </a>

                <a href="javascript:void(0);" class="sidebarCollapse btn btn-sm btn-light border mr-2" data-placement="bottom" aria-label="Abrir menu lateral">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
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

                <span class="navbar-brand mb-0 h5 text-dark text-truncate">CARNICERIA FRANCO</span>

            </div>

            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle d-flex align-items-center text-dark"
                        id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-user mr-2"></i>
                        <span class="font-weight-bold d-none d-sm-inline">{{ Auth::user()->name ?? 'Usuario' }}</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right shadow-sm" aria-labelledby="userProfileDropdown">
                        <div class="px-3 py-2 border-bottom">
                            <h6 class="mb-0">{{ Auth::user()->name ?? 'Usuario' }}</h6>
                            <small class="text-muted">{{ Auth::user()->profile ?? 'Perfil' }}</small>
                        </div>

                        <a href="user_profile.html" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i>
                            <span>Mi Perfil</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                <span>Cerrar sesion</span>
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </header>
</div>

