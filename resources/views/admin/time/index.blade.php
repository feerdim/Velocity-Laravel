@extends('layouts.app', ['title' => 'Schedule Management', 'subTitle' => 'Schedule Time' , 'link' => 'admin.time.index'])

@section('content')

<div class="row" style="min-height: 75vh">
    <section class="col-6">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="ni ni-time-alarm"></i> Schedule Time All Games</h4>
                @can('schedules.create')
                    <a href="{{ route('admin.time.create') }}" class="btn btn-primary" style="padding-top: 10px;"><i class="fa fa-plus-circle"></i> CREATE</a>
                @endcan
            </div>

            <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-flush" id="datatable-basic">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th scope="col">START</th>
                            <th scope="col" style="width: 15%;text-align: center">ACTION</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse ($times as $time)
                                <tr class="text-center">
                                    <td>{{ date("H:i", strtotime($time->start)) }}</td>
                                    <td class="text-center">
                                        @can('mmrs.edit')
                                            <a href="{{ route('admin.time.edit', $time->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                        @endcan
                                        @can('mmrs.delete')
                                            <button onClick="SaveId(this.id)" data-toggle="modal" data-target="#deleteModal" class="btn btn-sm btn-danger" id="{{ $time->id }}">
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
    <div class="col-6">
        <div class="card">
            <div class="card-header bg-transparent">
              <h3 class="mb-0">Cara Validasi</h3>
            </div>
            <div class="card-body">
              <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                <div class="timeline-block">
                  <span class="timeline-step badge-success">
                    1
                  </span>
                  <div class="timeline-content">
                    <small class="text-muted font-weight-bold">Login Akun Steam</small>
                    <p class=" text-sm mt-1 mb-0">Buka aplikasi steam anda dan segera login</p>
                  </div>
                </div>
                <div class="timeline-block">
                  <span class="timeline-step badge-danger">
                    2
                  </span>
                  <div class="timeline-content">
                    <small class="text-muted font-weight-bold">Manual Verifikasi</small>
                    <p class=" text-sm mt-1 mb-0">Masukan Account ID dari user sesuai dengan game yang mereka mainkan</p>
                  </div>
                </div>
                <div class="timeline-block">
                  <span class="timeline-step badge-info">
                    3
                  </span>
                  <div class="timeline-content">
                    <small class="text-muted font-weight-bold">Update Status</small>
                    <p class=" text-sm mt-1 mb-0">Jika akun player terdaftar di game tersebut, klik tombol yes di samping</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
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
        <div class="modal-body">Silahkan pilih "Delete" di bawah untuk detele time</div>
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
                        url: "{{ route("admin.time.index") }}/"+idTemp,
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