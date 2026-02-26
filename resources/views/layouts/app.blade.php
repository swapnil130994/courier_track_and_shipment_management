<!DOCTYPE html>
<html>
<head>
    <title>Courier & Shipment Panel</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        <!-- Toggle button for mobile -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <span class="navbar-brand fw-bold ms-2">
            🚚 Courier Panel
        </span>

        <div class="d-flex align-items-center text-white ms-auto">
            @auth
                <span class="me-3 d-none d-md-inline">
                    {{ auth()->user()->name }} ({{ auth()->user()->role }})
                </span>
            @endauth

            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>


<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-2 col-lg-2 bg-light sidebar collapse d-md-block"
             id="sidebarMenu">

            <ul class="nav nav-pills flex-column p-3">

                <li class="nav-item mb-2">
                    <a href="{{ url('/dashboard') }}"
                       class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="{{ url('/shipments') }}"
                       class="nav-link {{ request()->is('shipments*') ? 'active' : '' }}">
                        Shipments
                    </a>
                </li>

                @auth
                    @if(auth()->user()->role == 'admin')

                        <li class="nav-item mb-2">
                            <a href="{{ url('/reports') }}"
                               class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                                Reports
                            </a>
                        </li>

                        <li class="nav-item mb-2">
                            <a href="{{ url('/staff/create') }}"
                               class="nav-link {{ request()->is('staff*') ? 'active' : '' }}">
                                Add Staff
                            </a>
                        </li>

                    @endif
                @endauth
            </ul>
        </div>

        <!-- CONTENT -->
        <main class="col-md-10 col-lg-10 ms-sm-auto px-md-4 py-4">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </main>

    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@yield('scripts')

</body>
</html>