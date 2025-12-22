@extends('layouts.admin')

@section('title', 'Production Batches')
@section('page-title', 'Production Batches')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.production.manager') }}">Production</a></li>
            <li class="breadcrumb-item active">Batches</li>
        </ol>
    </nav>
    <h1 class="page-title">
        <i class="fas fa-layer-group me-2"></i>Production Batches
    </h1>
</div>

<div class="card">
    <div class="card-body">
        @livewire('admin.production-batches')
    </div>
</div>
@endsection
