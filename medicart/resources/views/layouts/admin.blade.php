<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — MediCart')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-light vh-100 position-sticky top-0 p-3" style="width: 240px;">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-4 text-light text-decoration-none">
            <i class="fa-solid fa-capsules fa-lg me-2"></i>
            <span class="fs-5 fw-bold">MediCart Admin</span>
        </a>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link text-light {{ request()->routeIs('admin.dashboard') ? 'active bg-success' : '' }}">
                    <i class="fa-solid fa-gauge me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.medicines.index') }}" class="nav-link text-light {{ request()->routeIs('admin.medicines.*') ? 'active bg-success' : '' }}">
                    <i class="fa-solid fa-pills me-2"></i> Medicines
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="nav-link text-light {{ request()->routeIs('admin.categories.*') ? 'active bg-success' : '' }}">
                    <i class="fa-solid fa-tags me-2"></i> Categories
                </a>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="nav-link text-light {{ request()->routeIs('admin.orders.*') ? 'active bg-success' : '' }}">
                    <i class="fa-solid fa-receipt me-2"></i> Orders
                </a>
            </li>
            <li>
                <a href="{{ route('admin.customers.index') }}" class="nav-link text-light {{ request()->routeIs('admin.customers.*') ? 'active bg-success' : '' }}">
                    <i class="fa-solid fa-users me-2"></i> Customers
                </a>
            </li>
            <li>
                <a href="{{ route('home') }}" class="nav-link text-light">
                    <i class="fa-solid fa-store me-2"></i> View Storefront
                </a>
            </li>
        </ul>
        <hr class="text-secondary">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Logout ({{ auth()->user()->name }})
            </button>
        </form>
    </div>

    <!-- Main content -->
    <div class="flex-grow-1 p-4" style="background:#f4f6f8; min-height:100vh;">
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
