@extends('layouts.app', ['title' => 'Games', 'link' => 'admin.game.index', 'subTitle' => $game->name . ' | solo'])

@section('content')
<div class="row" style="min-height: 75vh">
    <section class="col-12">

        <div class="section-body">

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="{{ $game->image }}" alt="image" style="width: 50px">
                        <h4 class="ml-3">SOLO | Type</h4>
                    </div>
                    @can('solos.create')
                        <div class="input-group-prepend">
                            <a href="{{ route('admin.solo.create', ['game'=> $game->id, 'solo' => $game->solo->id]) }}" class="btn btn-primary" style="padding-top: 10px;"><i class="fa fa-plus-circle"></i> TAMBAH</a>
                        </div>
                    @endcan
                </div>

                <div class="card-body">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr class="text-center">
                                <th scope="col">Name</th>
                                <th scope="col">Minimal Player</th>
                                <th scope="col">Maximal Player</th>
                                <th scope="col">Crown Price</th>
                                <th scope="col">Awards</th>
                                <th scope="col" style="width: 15%;text-align: center">AKSI</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($game->solo->types()->withPivot('id')->get() as $type)
                                    <tr class="text-center">
                                        <td>{{ $type->name }}</td>
                                        <td>{{ $type->min_player }}</td>
                                        <td>{{ $type->max_player }}</td>
                                        <td>{{ $type->crown_price }}</td>
                                        <td>
                                            <a href="{{ route('admin.award.show', ['game' => $game->id, 'type' => $type->id]) }}" class="btn btn-info">Awards</a>
                                        </td>
                                        <td class="text-center">
                                            
                                            @can('solos.delete')
                                                <button onClick="SaveId({{ $type->pivot->id }})" data-toggle="modal" data-target="#deleteModal" class="btn btn-sm btn-danger" id="{{ $game->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                    
                                @empty
                                    <tr>
                                        <td colspan="5">No Data Found</td>
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
        <div class="modal-body">Silahkan pilih "Delete" di bawah untuk detele type di game {{ $game->name }}</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <form action="{{ route('admin.solo.destroy') }}" method="POST">
            @csrf
            @method("DELETE")
                <input id="solo_name" type="hidden" name="id">
                <input type="hidden" name="game_id" value="{{ $game->id }}">
                <button class="btn btn-danger" style="cursor: pointer" type="submit">Delete</button>
        </form>
        </div>
      </div>
    </div>
</div>

<script>
    function SaveId(id) {
        document.getElementById("solo_name").value = id
    }
</script>
@endsection