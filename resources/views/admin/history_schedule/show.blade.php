@extends('layouts.app', ['title' => 'History Detail', 'subTitle' => $game->name . ' | ' . $type->name .' ' . date('h:i A', strtotime($player_schedule->schedule->start)) , 'link' => 'admin.history_schedule.index'])

@section('content')

<div class="row" style="min-height: 75vh">
    <section class="{{ $player_schedule->game_status == 'playing' ?  'col-md-8 col-lg-8' : 'col-md-12 col-lg-12'}} col-12 col-sm-12">
        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4><i class="fas fa-users"></i> Player Lists</h4>
                    <div class="d-flex flex-column">
                        <h4>Game Status</h4>
                        @if ($player_schedule->game_status == 'pending')
                            <span href="#" class="badge badge-pill badge-default">{{ $player_schedule->game_status }}</span>
                        @elseif ($player_schedule->game_status == 'playing')
                            <span href="#" class="badge badge-pill badge-info">{{ $player_schedule->game_status }}</span>
                        @elseif ($player_schedule->game_status == 'finish')
                            <span href="#" class="badge badge-pill badge-success">{{ $player_schedule->game_status }}</span>
                        @else
                            <span href="#" class="badge badge-pill badge-danger">{{ $player_schedule->game_status }}</span>    
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="{{ $player_schedule->game_status != 'playing' ? 'table-responsive' : '' }}">
                        <table class="{{ $player_schedule->game_status == 'playing' ? 'table-responsive' : '' }} table table-bordered table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">PLAYER EMAIL</th>
                                <th scope="col">Account ID</th>
                                <th scope="col">Account Name</th>
                                <th scope="col">Win Total</th>
                                <th scope="col">Lose Total</th>
                                <th scope="col">Final Score</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $detail)
                                    <tr>
                                        <td>{{ $detail->player->email }}</td>
                                        <td>{{ $detail->player->activations->where('game_id', $game->id)->first()->account_id }}</td>
                                        <td>{{ $detail->player->activations->where('game_id', $game->id)->first()->account_name ? $detail->player->activations->where('game_id', $game->id)->first()->account_name : '-'}}</td>
                                        <td>
                                            <span style="display: none">{{ $schedules->where('player_id',$detail->player->id)->first()->win_count }}</span>
                                            <form action="{{ route('admin.history_schedule.setwin', ['player_schedule' => $player_schedule->id, 'player' => $detail->player->id]) }}" method="POST">
                                                @csrf
                                                <input 
                                                    type="number" 
                                                    name="win_count"
                                                    placeholder="win total"
                                                    value="{{ $schedules->where('player_id',$detail->player->id)->first()->win_count }}"
                                                    required
                                                    @if ($player_schedule->game_status != 'playing' || $schedules->where('player_id',$detail->player->id)->first()->win_count != 0)
                                                    disabled
                                                    @endif/>
                                            </form>
                                        </td>
                                        <td>
                                            <span style="display: none">{{ $schedules->where('player_id',$detail->player->id)->first()->lose_count }}</span>
                                            <form action="{{ route('admin.history_schedule.setlose', ['player_schedule' => $player_schedule->id, 'player' => $detail->player->id]) }}" method="POST">
                                                @csrf
                                                <input 
                                                    type="number"
                                                    name="lose_count"
                                                    placeholder="lose total"
                                                    value="{{ $schedules->where('player_id',$detail->player->id)->first()->lose_count }}"
                                                    required
                                                    @if ($player_schedule->game_status != 'playing' || $schedules->where('player_id',$detail->player->id)->first()->lose_count != 0)
                                                    disabled
                                                    @endif/>
                                            </form>
                                        </td>
                                        <td>{{ $schedules->where('player_id',$detail->player->id)->first()->win_count - $schedules->where('player_id',$detail->player->id)->first()->lose_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
    @if ($player_schedule->game_status == 'playing')
        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
            <div class="section-body">

                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-crown"></i> Assign Winner</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.history_schedule.winner', ['player_schedule' => $player_schedule->id]) }}" method="POST">
                            @csrf
                            @forelse ($awards as $index => $award)
                                <div class="form-group">
                                    <label for="{{ $award->id }}" class="form-control-label d-flex justify-content-between">
                                        <div>
                                            Rank {{ $index+1 }}                                             
                                        </div>
                                        <div>
                                            <i class="fas fa-crown text-yellow ml-4"></i>
                                            {{ $award->nominal }}
                                        </div>
                                    </label>
                                    <input 
                                        name="{{ $award->nominal }}" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        type="email" 
                                        id="{{ $award->id }}" 
                                        placeholder="input player email"
                                        required>
                                </div>
                            @empty
                                <p>No Awards</p>
                            @endforelse
                            <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i> Assign</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
    @endif
</div>





@endsection