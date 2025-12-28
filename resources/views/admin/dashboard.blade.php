@extends('layouts.app')

@section('content')
<div class="container py-3" style="padding-top: 80px;"> 

   
    <div class="mb-4 text-center" style="margin-top: 50px;">
    <h4 class="fw-bold">Admin Dashboard</h4>
</div>

   
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.list') }}">
            
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $totalUsers }}</h5>
                        <p class="card-text small">Total Users</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.list', ['filter' => 'reporter']) }}">

                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $reporters }}</h5>
                        <p class="card-text small">Reporters</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.list', ['filter' => 'designated']) }}">

                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $designated }}</h5>
                        <p class="card-text small">Designated</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.pending') }}">

                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $pending }}</h5>
                        <p class="card-text small">Pending Approval</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    
    <div class="d-grid gap-2 mb-3">
        <a href="{{ route('admin.pending') }}" class="btn btn-warning btn-lg rounded-pill">
            Pending Registrations
        </a>
        <a href="{{ route('admin.create') }}" class="btn btn-primary btn-lg rounded-pill">
            Add User
        </a>
    </div>

</div>
@endsection
