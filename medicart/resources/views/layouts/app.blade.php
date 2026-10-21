<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MediCart — Online Pharmacy')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fa-solid fa-capsules me-1"></i> MediCart
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('medicines.index') }}">Medicines</a></li>
                </ul>
                <form class="d-flex me-3" role="search" method="GET" action="{{ route('medicines.index') }}">
                    <input class="form-control form-control-sm" type="search" name="q" placeholder="Search medicines..." value="{{ request('q') }}">
                </form>
                <ul class="navbar-nav align-items-lg-center">
                    @auth
                        @php
                            $cartCount = auth()->user()->cartItems()->sum('quantity');
                        @endphp
                        @if (auth()->user()->isAdmin())
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge"></i> Admin Panel</a></li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                    <i class="fa-solid fa-cart-shopping"></i> Cart
                                    @if ($cartCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-light text-success border border-success">
                                            {{ $cartCount }}
                                            <span class="visually-hidden">items in cart</span>
                                        </span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">My Orders</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('address.index') }}">My Addresses</a></li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-user"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fa-solid fa-user me-2"></i>View Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="btn btn-light btn-sm text-success fw-semibold ms-lg-2" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        <div class="container py-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="mb-3">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.history.back()">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back
                </button>
            </div>

            @yield('content')
        </div>
    </main>

    <footer class="bg-dark text-light py-4 mt-auto">
        <div class="container text-center small">
            &copy; {{ date('Y') }} MediCart — Online Pharmacy System. Built with Laravel.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
