@extends('layouts.app', ['title' => 'Games', 'subTitle' => $game->name . ' | '. $type->name . ' | awards', 'link' => 'admin.game.index'])

@section('content')

<div class="row" style="min-height: 75vh">
    <section class="col-6">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-crown"></i> Awards</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-flush" id="datatable-basic">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th scope="col">NOMINAL</th>
                            <th scope="col" style="width: 15%;text-align: center">ACTION</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse ($type->awards as $award)
                                <tr class="text-center">
                                    <td>{{ $award->nominal }}</td>
                                    <td class="text-center">
                                        @can('awards.delete')
                                            <button onClick="SaveId(this.id)" data-toggle="modal" data-target="#deleteModal" class="btn btn-sm btn-danger" id="{{ $award->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">No Data Found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
    <section class="col-6">

        <div class="section-body">

            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-crown"></i> Create New Award</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.award.store', ['game' => $game->id, 'type' => $type->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>NOMINAL</label>
                            <input type="number" name="nominal" value="{{ old('nominal') }}" placeholder="Ex:100" class="form-control @error('nominal') is-invalid @enderror">

                            @error('nominal')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i> SIMPAN</button>
                        <button class="btn btn-warning btn-reset" type="reset"><i class="fa fa-redo"></i> RESET</button>

                    </form>
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
        <div class="modal-body">Silahkan pilih "Delete" di bawah untuk detele award</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <button class="btn btn-danger"  style="cursor: pointer" onClick="Delete('{{ $game->id }}','{{ $type->id }}')">Delete</button>
        </div>
      </div>
    </div>
</div>

<script>
      var idTemp = null;
    function SaveId(id) {
        idTemp = id
    }
    // /game/{game}/type/{type}/award/{id}
    //ajax delete
    function Delete(gameId,typeId)
        {
            var token = $("meta[name='csrf-token']").attr("content");

            jQuery.ajax({
                        url: `/admin/game/${gameId}/type/${typeId}/award/${idTemp}`,
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

