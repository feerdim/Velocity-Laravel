@extends('layouts.app', ['title' => 'Types', 'link' => 'admin.type.index'])

@section('content')
<div class="row" style="min-height: 75vh">
    <section class="col-12">

        <div class="section-body">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4><i class="fas fa-gamepad"></i> Types</h4>
                    @can('types.create')
                        <a href="{{ route('admin.type.create') }}" class="btn btn-primary" style="padding-top: 10px;"><i class="fa fa-plus-circle"></i> CREATE</a>            
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr class="text-center">
                                <th scope="col">Name</th>
                                <th scope="col">Min Player</th>
                                <th scope="col">Max Player</th>
                                <th scope="col">Crown Price</th>
                                <th scope="col" style="width: 15%;text-align: center">ACTION</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($types as $type)
                                    <tr class="text-center">
                                        <td>{{ $type->name }}</td>
                                        <td>{{ $type->min_player }}</td>
                                        <td>{{ $type->max_player }}</td>
                                        <td>{{ $type->crown_price }}</td>
                                        <td class="text-center">
                                            @can('games.edit')
                                                <a href="{{ route('admin.type.edit', $type->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-pencil-alt"></i>
                                                </a>
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
        <div class="modal-body">Silahkan pilih "Delete" di bawah untuk detele game</div>
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
                        url: "{{ route("admin.game.index") }}/"+idTemp,
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