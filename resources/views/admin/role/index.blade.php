@extends('layouts.app', ['title' => 'User Management', 'subTitle' => 'Roles', 'link' => 'admin.role.index'])

@section('content')

<div class="row" style="min-height: 75vh">
    <section class="col-12">

        <div class="section-body">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4><i class="fas fa-unlock"></i> Roles</h4>
                    @can('roles.create')
                        <a href="{{ route('admin.role.create') }}" class="btn btn-primary" style="padding-top: 10px;"><i class="fa fa-plus-circle"></i> CREATE</a>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width: 15%">ROLE NAME</th>
                                <th scope="col">PERMISSIONS</th>
                                <th scope="col" style="width: 15%;text-align: center">ACTION</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($roles as $no => $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                    <td class="d-flex flex-wrap">
                                        @foreach($role->getPermissionNames() as $permission)
                                            <button class="btn btn-sm btn-success mb-1 mt-1 mr-1">{{ $permission }}</button>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        @can('roles.edit')
                                            <a href="{{ route('admin.role.edit', $role->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                        @endcan
                                        
                                        @can('roles.delete')
                                            <button onClick="SaveId(this.id)" data-toggle="modal" data-target="#deleteModal" class="btn btn-sm btn-danger" id="{{ $role->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
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
        <div class="modal-body">Silahkan pilih "Delete" di bawah untuk detele role</div>
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
                        url: "{{ route("admin.role.index") }}/"+idTemp,
                        data:     {
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