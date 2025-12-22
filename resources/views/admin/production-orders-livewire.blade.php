@extends('layouts.admin')

@section('title', 'Production Orders')
@section('page-title', 'Production Orders')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.production.manager') }}">Production</a></li>
            <li class="breadcrumb-item active">Orders</li>
        </ol>
    </nav>
    <h1 class="page-title">
        <i class="fas fa-shopping-cart me-2"></i>Production Orders
    </h1>
</div>

<div class="card">
    <div class="card-body">
        @livewire('admin.production-orders')
    </div>
</div>
@endsection
