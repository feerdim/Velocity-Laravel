@extends('layouts.app', ['title' => 'My Schedule', 'link' => 'admin.history_schedule.index'])

@section('content')

<div class="row" style="min-height: 75vh">
    <section class="col-12">
        <div class="section-body">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4><i class="fas fa-users"></i> My Schedule</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr style="text-align: center">
                                <th scope="col">GAME</th>
                                <th scope="col">CATEGORY</th>
                                <th scope="col">DATE</th>
                                <th scope="col">GAME STATUS</th>
                                <th scope="col">GAME_ROOM ID</th>
                                <th scope="col">ADMIN NAME</th>
                                <th scope="col" style="width: 15%;text-align: center">PLAYERS DETAIL</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                    $group_by_game = $histories->unique(function ($item) {
                                        return $item['game_id'].$item['game_master_id'];
                                    });
                                    $before = $histories->first();
                                @endphp

                                @forelse ($group_by_game as $index=>$history)
                                    {{-- @if($history->game_id==$before->game_id && $history->game_master_id==$before->game_master_id && $history->game_status!=$before->game_status) --}}
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
                                            @if ($history->game_status == 'pending')
                                                <span href="#" class="badge badge-pill badge-default">{{ $history->game_status }}</span>
                                            @elseif ($history->game_status == 'playing')
                                                <span href="#" class="badge badge-pill badge-info">{{ $history->game_status }}</span>
                                            @elseif ($history->game_status == 'finish')
                                                <span href="#" class="badge badge-pill badge-success">{{ $history->game_status }}</span>
                                            @else
                                                <span href="#" class="badge badge-pill badge-danger">{{ $history->game_status }}</span>    
                                            @endif
                                            @foreach ($histories as $index=>$check)
                                            @if($history->game_master_id==$check->game_master_id && $history->game_status!=$check->game_status)
                                            @if ($check->game_status == 'pending')
                                                <span href="#" class="badge badge-pill badge-default">{{ $check->game_status }}</span>
                                            @elseif ($check->game_status == 'playing')
                                                <span href="#" class="badge badge-pill badge-info">{{ $check->game_status }}</span>
                                            @elseif ($check->game_status == 'finish')
                                                <span href="#" class="badge badge-pill badge-success">{{ $check->game_status }}</span>
                                            @else
                                                <span href="#" class="badge badge-pill badge-danger">{{ $check->game_status }}</span>    
                                            @endif
                                            @endif
                                            @endforeach
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
                                            <a href="{{ route('admin.vs_one.show', ['game_master' => $history->game_master, 'type' => $history->type_id]) }}" class="btn btn-sm btn-default" id="{{ $history->id }}">
                                                Players Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @php
                                        $before = $history;
                                    @endphp
                                    {{-- @endif --}}
                                @empty
                                    <tr>
                                        <td colspan="10">No Data Found!</td>
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
</script>

@endsection