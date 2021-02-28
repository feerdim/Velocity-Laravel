@extends('layouts.app', ['title' => 'Solo Schedules', 'subTitle' => $game->name . ' ' . $type->name . ' ' . $solo_schedule->schedule->start, 'link' => 'admin.solo_schedule.index'])

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
    {{-- <section class="col-6">

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4><i class="fas fa-users"></i> Player Lists</h4>
                    <div>
                        @can('game_masters.create')
                            <a href="{{ route('admin.game_master.create', $solo_schedule->id) }}" class="btn btn-sm btn-success">
                                Handle This Game
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">PLAYER NAME</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $detail)
                                    <tr>
                                        <td>{{ $detail->player->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section> --}}

    {{-- <div class="col-6">
        <div class="card">
            <div class="card-header bg-transparent">
              <h3 class="mb-0">Ketentuan Game</h3>
            </div>
            <div class="card-body">
              <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                <div class="timeline-block">
                  <span class="timeline-step badge-success">
                    1
                  </span>
                  <div class="timeline-content">
                    <small class="text-muted font-weight-bold">Login Akun Steam</small>
                    <p class=" text-sm mt-1 mb-0">Buka aplikasi steam anda dan segera login</p>
                  </div>
                </div>
                <div class="timeline-block">
                  <span class="timeline-step badge-danger">
                    2
                  </span>
                  <div class="timeline-content">
                    <small class="text-muted font-weight-bold">Manual Verifikasi</small>
                    <p class=" text-sm mt-1 mb-0">Masukan Account ID dari user sesuai dengan game yang mereka mainkan</p>
                  </div>
                </div>
                <div class="timeline-block">
                  <span class="timeline-step badge-info">
                    3
                  </span>
                  <div class="timeline-content">
                    <small class="text-muted font-weight-bold">Update Status</small>
                    <p class=" text-sm mt-1 mb-0">Jika akun player terdaftar di game tersebut, klik tombol yes di samping</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div> --}}
 {{-- {{ $schedules }} --}}
</div>





@endsection