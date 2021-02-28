@extends('layouts.app', ['title' => 'Team Schedules', 'subTitle' => $game->name . ' ' . $type->name . ' ' . $team_schedule->schedule->start, 'link' => 'admin.team_schedule.index'])

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
          <h4><i class="ni ni-controller mr-2"></i>Min Player : {{ $type->min_player }} | Max Player : {{ $type->max_player }}</h4>
      </div>
    </div>
  </div>
</div>
<div class="d-flex" style="min-height: 75vh;flex-wrap:no-wrap; overflow-x:auto">
  @forelse ($batches as $key => $batch)
    <section class="col-4">

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4><i class="fas fa-users"></i> {{ $key }}</h4>
                    <div>
                      {{-- @php
                          dd($batch);
                      @endphp --}}
                      @if (count($batch) >= $type->min_player)
                        @can('game_masters.create')
                            {{-- {{  $serializeArray = serialize($batch) }}  --}}
                            <a href="{{ route('admin.game_master.create', $batch) }}" class="btn btn-sm btn-success">
                                Handle This Game
                            </a>
                        @endcan
                      @else
                        <span class="badge badge-danger">Waiting for players</span>
                      @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">PLAYER NAME</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($batch as $player)
                                    <tr>
                                        <td>{{ $player->player->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
      
  @empty
      
  @endforelse

</div>

@endsection