@extends('layouts.app', ['title' => 'History', 'link' => 'admin.history_schedule.all'])

@section('content')

<div class="row" style="min-height: 75vh">
    <section class="col-12">
        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4><i class="fas fa-users"></i> Histories 1 VS 1</h4>
                </div>
                <div class="card-body">
                    <div class="">
                        <table class="table-responsive table table-bordered table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr style="text-align: center">
                                <th scope="col">GAME</th>
                                <th scope="col">CATEGORY</th>
                                <th scope="col">DATE</th>
                                <th scope="col">GAME_ROOM ID</th>
                                <th scope="col" style="width: 15%; text-align: center">PLAYERS DETAIL</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                    $group_by_game = $vsOnes->unique(function ($item) {
                                        return $item['game_id'].$item['game_master_id'];
                                    });
                                @endphp
                                @forelse ($group_by_game as $index=> $history)
                                    <tr style="text-align: center">
                                        <td style="display: flex; align-items:center;">
                                            <div>
                                                <img src={{ $history->game->image }} style="width:30px;height:30px;margin-right:10px" />
                                            </div>
                                            <span style="margin: 0">{{ $history->game->name }}</span>
                                        </td>
                                        <td>
                                            @if ($history->is_solo)
                                                SOLO
                                            @else
                                                TEAM
                                            @endif
                                        </td>
                                        <td>{{ dateID($history->created_at) }}</td>
                                        <td>
                                            @if ($history->game_master)
                                                {{ $history->game_master->room_id }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.vs_one.show',['game_master' => $history->game_master_id, 'type' => $history->type_id]) }}" class="btn btn-sm btn-default" id="{{ $history->id }}">
                                                Players Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">No Data Found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4><i class="fas fa-users"></i> Histories My Schedule</h4>
                </div>

                <div class="card-body">
                    <div class="">
                        <table class="table-responsive table table-bordered table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr style="text-align: center">
                                <th scope="col">GAME</th>
                                <th scope="col">CATEGORY</th>
                                <th scope="col">TYPE</th>
                                <th scope="col">TIME</th>
                                <th scope="col">DATE</th>
                                <th scope="col">PRICE</th>
                                <th scope="col">GAME STATUS</th>
                                <th scope="col">GAME_ROOM ID</th>
                                <th scope="col">ADMIN NAME</th>
                                <th scope="col" style="width: 15%;text-align: center">PLAYERS DETAIL</th>
                                <th scope="col" style="width: 15%;text-align: center">REFUND</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                    $group_by_game = $histories->unique(function ($item) {
                                        return $item['game_id'].$item['schedule_id'].$item['type_id'].$item['game_status'].$item['game_master_id'];
                                    });
                                @endphp
                                
                                @forelse ($histories as $index=> $history)
                                    <tr style="text-align: center">
                                        <td style="display: flex; align-items:center;">
                                            <div>
                                                <img src={{ $history->game->image }} style="width:30px;height:30px;margin-right:10px" />
                                            </div>
                                            <span style="margin: 0">{{ $history->game->name }}</span>
                                        </td>
                                        <td>
                                            @if ($history->is_solo)
                                                SOLO
                                            @else
                                                TEAM
                                            @endif
                                        </td>
                                        <td>{{ $history->type->name }}</td>
                                        <td>{{ date("H:i", strtotime($history->schedule->start)) }}</td>
                                        <td>{{ dateID($history->created_at) }}</td>
                                        <td>{{ $history->type->crown_price }}</td>
                                        <td>
                                            @if ($history->game_status == 'pending')
                                                <span href="#" class="badge badge-pill badge-default">{{ $history->game_status }}</span>
                                            @elseif ($history->game_status == 'playing')
                                                <span href="#" class="badge badge-pill badge-info">{{ $history->game_status }}</span>
                                            @elseif ($history->game_status == 'finish')
                                                <span href="#" class="badge badge-pill badge-success">{{ $history->game_status }}</span>
                                            @else
                                                <span href="#" class="badge badge-pill badge-danger">{{ $history->game_status }}</span>    
                                            @endif
                                        </td>
                                        <td>
                                            @if ($history->game_master)
                                                {{ $history->game_master->room_id }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($history->game_master)
                                                {{ $history->game_master->user->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.history_schedule.show',['player_schedule' => $history->id,'game' => $history->game_id, 'type' => $history->type_id]) }}" class="btn btn-sm btn-default" id="{{ $history->id }}">
                                                Players Detail
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            @if ($history->game_status === 'pending')
                                                <button onClick="SaveId(this.id)" data-toggle="modal" data-target="#deleteModal" class="btn btn-sm btn-danger" id="{{ $history->id }}">
                                                    REFUND
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">No Data Found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- DELETE Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Refund confirmation ?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Are you sure want to refund (cancel) this game ?</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <button class="btn btn-danger" style="cursor: pointer" onClick="Delete()">Refund</button>
        </div>
      </div>
    </div>
</div>

<script>
    var idTemp = null;
    function SaveId(id) {
        idTemp = id
    }
    //ajax delete
    function Delete()
        {
            var token = $("meta[name='csrf-token']").attr("content");

            jQuery.ajax({
                url: `/admin/history_schedule/refund/${idTemp}`,
                        success: function (response) {
                            if (response.status == "success") {
                                location.reload();
                            }else{
                                location.reload();
                            }
                        }
                    });
        }
</script>

@endsection