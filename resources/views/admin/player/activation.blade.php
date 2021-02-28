@extends('layouts.app', ['title' => 'Players', 'subTitle' => $player->name.' | Activation ' , 'link' => 'admin.player.index'])

@section('content')

<div class="row" style="min-height: 75vh">
    <div class="col-6">
        <div class="row">
            @foreach ($player->activations()->orderBy('id', 'desc')->get() as $activation)
                <div class="col-12">
                    <div class="card">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <!-- Avatar -->
                                    <img alt="Image placeholder" src=" {{ $activation->game->image }}">
                                </div>
                                <div class="col ml--2">
                                    <p class="text-sm mb-0">Account ID : <span style="font-weight: bold">{{ $activation->account_id }}</span></p>
                                    <p class="text-sm mb-0">Account NAME : <span style="font-weight: bold">{{ $activation->account_name }}</span></p>
                                    <span class="text-{{ $activation->status == 1 ? 'success' : 'danger' }}">●</span>
                                    <small>{{ $activation->status == 1 ? 'Validate' : 'Not Validated' }}</small>
                                </div>
                                <div class="col-auto">
                                    {{-- <form action=""> --}}
                                        <label class="custom-toggle">
                                            <input class="status" playerId="{{ $player->id }}" onclick="changeStatus('{{ $player->id }}','{{ $activation->id }}',this)" type="checkbox" {{ $activation->status == 1 ? 'checked' : '' }}>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                        </label>
                                    {{-- </form> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
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

<script>
    function changeStatus(playerId,activationId,cb) {
        var token = $("meta[name='csrf-token']").attr("content");

        jQuery.ajax({
                    url: `/admin/player/${playerId}/activation`,
                    data:   {
                        "activation_id": activationId,
                        "status": cb.checked,
                        "_token": token
                    },
                    type: 'PUT',
                    success: function (response) {
                      console.log(response,">>>RESPONS");
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