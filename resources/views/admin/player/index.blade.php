@extends('layouts.app', ['title' => 'Players', 'link' => 'admin.player.index'])

@section('content')

<div class="row" style="min-height: 75vh">
    <section class="col-12">

        <div class="section-body">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4><i class="fas fa-users"></i> Players</h4>
                    {{-- @can('players.create')
                        <a href="{{ route('admin.player.create') }}" class="btn btn-primary" style="padding-top: 10px;"><i class="fa fa-plus-circle"></i> CREATE</a>
                    @endcan --}}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">IMAGE</th>
                                <th scope="col">NAME</th>
                                <th scope="col">EMAIL</th>
                                <th scope="col">CROWN</th>
                                <th scope="col">SCORE</th>
                                <th scope="col">ACTIVATION ACCOUNT</th>
                                {{-- <th scope="col" style="width: 15%;text-align: center">ACTION</th> --}}
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($players as $no => $player)
                                    <tr>
                                        <td>
                                            <img src="{{ $player->avatar }}" alt="avatar" style="width: 81px; height:81px; object-fit:contain">
                                        </td>
                                        <td>{{ $player->name }}</td>
                                        <td>{{ $player->email }}</td>
                                        <td>{{ $player->crown }}</td>
                                        <td>{{ $player->score }}</td>
                                        <td>
                                            <div aria-checked="d-flex">
                                                @forelse ($player->activations as $activation)
                                                {{-- <span class="badge mx-2 badge-{{ $activation->status == 1 ? 'primary' : 'danger' }}">{{ $activation->game->name }}</span> --}}
                                                    <a href="{{ route('admin.playerActivation.show', $player->id) }}" class="text-white btn btn-sm btn-{{ $activation->status == 1 ? 'success' : 'warning' }}" data-toggle="tooltip" data-placement="top" title="{{ $activation->status == 1 ? 'validated' : 'not validated' }}">
                                                        {{ $activation->game->name }}
                                                    </a>
                                                @empty
                                                    -
                                                @endforelse
                                            </div>
                                        </td>
                                        {{-- <td class="text-center">
                                            @can('players.delete')
                                                <button onClick="SaveId(this.id)" data-toggle="modal" data-target="#deleteModal" class="btn btn-sm btn-danger" id="{{ $player->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endcan
                                        </td> --}}
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