@extends('layouts.app', ['title' => 'Solo Schedules', 'link' => 'admin.solo_schedule.index'])

@section('content')

<div class="row" style="min-height: 75vh">
  <section class="col-12">
      <div class="section-body">

          <div class="card">
              <div class="card-header d-flex align-items-center justify-content-between">
                  <h4><i class="ni ni-controller"></i> {{ dateID(date("Y/m/d")) }}</h4>
              </div>

              <div class="card-body">
                  <div class="table-responsive">
                      <table class="table table-bordered table-flush" id="datatable-basic">
                          <thead class="thead-light">
                          <tr>
                              <th scope="col">TIME</th>
                              <th scope="col">COMPETITION LIST</th>
                          </tr>
                          </thead>
                          <tbody>
                              @forelse ($schedules as $schedule)
                                <tr>
                                  <td>{{ date("H:i", strtotime($schedule->start)) }}</td>
                                  <td class="d-flex flex-wrap">
                                    @php
                                        $game_in_schedule = $schedule->player_schedules->where('game_master_id','=',null)->unique(function ($item) {
                                          return $item['game_id'].$item['schedule_id'].$item['type_id'];
                                        });
                                    @endphp
                                    @forelse ($game_in_schedule->values()->all() as $player_schedule)
                                      <a href="{{ route('admin.solo_schedule.showDetail', ['solo_schedule' => $player_schedule->id, 'game' => $player_schedule->game_id, 'type' => $player_schedule->type_id ]) }}" class="btn btn-default mr-4">
                                        <span>{{ $player_schedule->game->name }} | {{ $player_schedule->game->solo->types->where('id', '=', $player_schedule->type_id)->first()->name }}</span>
                                        <span class="badge badge-md badge-circle badge-floating" style="border-radius: 0; border:none">
                                          <img src="{{ $player_schedule->game->image }}" alt="avatar" style="width: 100%;object-fit:cover">
                                        </span>
                                      </a>
                                    @empty
                                        <div>No Game</div>
                                    @endforelse
                                  </td>
                                </tr>
                              @empty
                                  <tr>
                                      <td colspan="7">No Data Found!</td>
                                  </tr>
                              @endforelse
                          </tbody>
                      </table>
                      {{-- {{ $schedules }} --}}
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
          <h5 class="modal-title" id="exampleModalLabel">Apakah Yakin Ingin Delete ?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Silahkan pilih "Delete" di bawah untuk detele user</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <button class="btn btn-danger"  style="cursor: pointer" onClick="Delete()">Delete</button>
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
                        url: "{{ route("admin.player.index") }}/"+idTemp,
                        data:   {
                            "id": idTemp,
                            "_token": token
                        },
                        type: 'DELETE',
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