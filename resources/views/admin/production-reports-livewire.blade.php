@extends('layouts.admin')

@section('title', 'Production Reports')
@section('page-title', 'Production Reports')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.production.manager') }}">Production</a></li>
            <li class="breadcrumb-item active">Reports</li>
        </ol>
    </nav>
    <h1 class="page-title">
        <i class="fas fa-chart-bar me-2"></i>Production Reports
    </h1>
</div>

<div class="card">
    <div class="card-body">
        @livewire('admin.production-reports')
    </div>
</div>
@endsection
