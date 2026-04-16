<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-success border-3 shadow-sm py-3">
    <div class="container-fluid px-4">
        
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <i class="bi bi-heart-pulse-fill text-success"></i> GymControl
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuGym">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuGym">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold text-white' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active fw-bold text-white' : '' }}" href="{{ route('clientes.index') }}">
                        <i class="bi bi-people-fill"></i> Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="bi bi-images"></i> Galería
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center mt-3 mt-lg-0 border-top border-secondary pt-3 pt-lg-0 border-lg-0 border-top-0">
                @auth
                    <span class="text-light me-3 fw-semibold">
                        <i class="bi bi-person-circle text-success me-1"></i> {{ Auth::user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button class="btn btn-danger btn-sm fw-bold px-3 d-flex align-items-center gap-2 shadow-sm" type="submit">
                            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                        </button>
                    </form>
                @endauth
            </div>
        </div>
        
    </div>
</nav>