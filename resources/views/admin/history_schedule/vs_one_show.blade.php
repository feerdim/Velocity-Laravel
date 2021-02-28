@extends('layouts.app', ['title' => 'History Detail', 'subTitle' => $game->name . ' | ' . $type->name, 'link' => 'admin.vs_one.index'])

@section('content')

<div class="row" style="min-height: 75vh">
    <section class="col-12 col-sm-12">
        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4><i class="fas fa-users"></i> Player Lists</h4>
                    @if ($schedules->first()->game_status == 'pending' || $schedules->first()->game_status == 'finish' || $schedules->first()->game_status == 'cancel')
                        <div class="d-flex flex-column">
                            <h4>Game Status</h4>
                            @if ($schedules->first()->game_status == 'pending')
                                <span href="#" class="badge badge-pill badge-default">{{ $schedules->first()->game_status }}</span>
                            @elseif ($schedules->first()->game_status == 'finish')
                                <span href="#" class="badge badge-pill badge-success">{{ $schedules->first()->game_status }}</span>
                            @elseif ($schedules->first()->game_status == 'cancel')
                                <span href="#" class="badge badge-pill badge-danger">{{ $schedules->first()->game_status }}</span>    
                            @endif
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table-responsive table table-bordered table-flush" id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">PLAYER EMAIL</th>
                                    <th scope="col">Account ID</th>
                                    <th scope="col">Account Name</th>
                                    <th scope="col">Image</th>
                                    @if ($schedules->first()->game_status == 'playing' || $schedules->first()->game_status == 'waiting')
                                        <th scope="col">Game Status</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $detail)
                                    <tr>
                                        <td>{{ $detail->player->email }}</td>
                                        <td>{{ $detail->player->activations->where('game_id', $game->id)->first()->account_id }}</td>
                                        <td>{{ $detail->player->activations->where('game_id', $game->id)->first()->account_name ? $detail->player->activations->where('game_id', $game->id)->first()->account_name : '-'}}</td>
                                        <td>
                                            <img src="/storage/schedule/{{ $detail->image }}" alt="no image" style="height:200px">
                                        </td>
                                        @if ($detail->game_status == 'playing')
                                            <td>
                                                <span href="#" class="badge badge-pill badge-info">{{ $detail->game_status }}</span>
                                            </td>
                                        @elseif ($detail->game_status == 'waiting')
                                            <td>
                                                <span href="#" class="badge badge-pill badge-success">{{ $detail->game_status }}</span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="col-12 col-sm-12">
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-crown"></i> Assign Winner</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vs_one.winner', ['game_master'=>$game_master])}}" method="POST">
                    @csrf
                    @forelse ($awards as $index => $award)
                        <div class="form-group">
                            <label for="{{ $award->id }}" class="form-control-label d-flex justify-content-between">
                                <div>Rank {{ $index+1 }}</div>
                                <div><i class="fas fa-crown text-yellow ml-4"></i>{{ $award->nominal }}</div>
                            </label>
                            <input 
                                name="{{ $award->nominal }}" 
                                class="form-control @error('email') is-invalid @enderror" 
                                type="email" 
                                id="{{ $award->id }}" 
                                placeholder="input player email"
                                @if ($schedules->first()->game_status == 'finish')
                                value="{{ $schedules->first()->winner }}"
                                disabled
                                @endif
                                required>
                        </div>
                    @empty
                        <p>No Awards</p>
                    @endforelse
                    @if ($schedules->first()->game_status != 'finish')
                    <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i> Assign</button>
                    @endif
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection