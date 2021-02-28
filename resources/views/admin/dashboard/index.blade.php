@extends('layouts.app', ['title' => 'Dashboard', 'link' => 'admin.dashboard.index'])

@section('content')

<div class="row" style="min-height: 75vh">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-gradient-primary border-0">
        <!-- Card body -->
        <div class="card-body">
            <div class="row">
            <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0 text-white">Total Games</h5>
                <span class="h2 font-weight-bold mb-0 text-white">{{ $total_game }}</span>
                {{-- <div class="progress progress-xs mt-3 mb-0">
                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%;"></div>
                </div> --}}
            </div>

            </div>
            <p class="mt-3 mb-0 text-sm">
            <a href="{{ route('admin.game.index') }}" class="text-nowrap text-white font-weight-600">See details</a>
            </p>
        </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-gradient-info border-0">
        <!-- Card body -->
        <div class="card-body">
            <div class="row">
            <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0 text-white">Topup Charges</h5>
                <span class="h2 font-weight-bold mb-0 text-white">{{ moneyFormat($total_transaction) }}</span>
                {{-- <div class="progress progress-xs mt-3 mb-0">
                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%;"></div>
                </div> --}}
            </div>
            </div>
            <p class="mt-3 mb-0 text-sm">
                <a href="{{ route('admin.transaction.index') }}" class="text-nowrap text-white font-weight-600">See details</a>
            </p>
        </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-gradient-danger border-0">
        <!-- Card body -->
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0 text-white">Registered Player</h5>
                    <span class="h2 font-weight-bold mb-0 text-white">{{ $total_player }} Players</span>
                    {{-- <div class="progress progress-xs mt-3 mb-0">
                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;"></div>
                    </div> --}}
                </div>

            </div>
            <p class="mt-3 mb-0 text-sm">
                <a href="{{ route('admin.player.index') }}" class="text-nowrap text-white font-weight-600">See details</a>
            </p>
        </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-gradient-default border-0">
        <!-- Card body -->
        <div class="card-body">
            <div class="row">
            <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0 text-white">Booked Games</h5>
                <span class="h2 font-weight-bold mb-0 text-white">{{ $total_competition }}</span>
            </div>
            </div>
            <p class="mt-3 mb-0 text-sm">
            <a href="{{ route('admin.history_schedule.index') }}" class="text-nowrap text-white font-weight-600">See details</a>
            </p>
        </div>
        </div>
    </div>
</div>

@endsection

