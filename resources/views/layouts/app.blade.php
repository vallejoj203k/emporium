<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gym Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light gym-shell">
    <div class="d-flex min-vh-100">
        <aside class="offcanvas-lg offcanvas-start gym-sidebar" tabindex="-1" id="gymSidebar" aria-labelledby="gymSidebarLabel">
            <div class="offcanvas-header d-lg-none">
                <h5 class="offcanvas-title" id="gymSidebarLabel">Gym Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column p-3 p-lg-4">
                <a class="d-flex align-items-center gap-2 mb-4 text-white text-decoration-none" href="{{ route('dashboard') }}">
                    <span class="sidebar-brand-icon">GA</span>
                    <div>
                        <div class="fw-semibold fs-5">Gym Admin</div>
                        <div class="small text-white-50">Management System</div>
                    </div>
                </a>

                <nav class="nav nav-pills flex-column gap-2 sidebar-nav">
                    <a class="nav-link sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    <a class="nav-link sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}"><i class="bi bi-graph-up-arrow me-2"></i>Reportes</a>
                    <a class="nav-link sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}"><i class="bi bi-people me-2"></i>Clientes</a>
                    <a class="nav-link sidebar-link {{ request()->routeIs('customer-memberships.*') ? 'active' : '' }}" href="{{ route('customer-memberships.index') }}"><i class="bi bi-card-checklist me-2"></i>Membresías</a>
                    <a class="nav-link sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}"><i class="bi bi-wallet2 me-2"></i>Pagos</a>
                    @if(auth()->user()?->role?->slug === 'admin')
                    <a class="nav-link sidebar-link {{ request()->routeIs('membership-plans.*') ? 'active' : '' }}" href="{{ route('membership-plans.index') }}"><i class="bi bi-box-seam me-2"></i>Planes</a>
                    @endif
                </nav>

                <div class="mt-auto pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-light w-100 btn-lg sidebar-logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Salir
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-grow-1 main-pane">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
                <div class="container-fluid px-3 px-lg-4">
                    <button class="btn btn-dark d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#gymSidebar" aria-controls="gymSidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold">@yield('title', 'Gym Management System')</span>
                    </div>
                </div>
            </nav>

            <main class="container-fluid py-4 px-3 px-lg-4">
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>