@extends('layouts.app', ['title' => 'Transaction Management','subTitle' => 'Transaction History', 'link' => 'admin.transaction.index'])

@section('content')

<div class="row" style="min-height: 75vh">
    <section class="col-12">

        <div class="section-body">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4><i class="fas fa-users"></i> Transaction Histories</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Transaction No</th>
                                <th scope="col">Crown</th>
                                <th scope="col">Grand Total</th>
                                <th scope="col">Payment Type</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date</th>
                                <th scope="col">Player Email</th>
                                {{-- <th scope="col">PRICE</th> --}}
                                {{-- <th scope="col" style="width: 15%;text-align: center">ACTION</th> --}}
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>
                                            {{-- <i class="fas fa-crown text-yellow mr-2"></i> --}}
                                            {{ $transaction->transaction_no }}
                                        </td>
                                        <td>
                                            <i class="fas fa-crown text-yellow mr-2"></i>
                                            {{ $transaction->crown_package->name }}
                                        </td>
                                        <td>{{ moneyFormat($transaction->grand_total) }}</td>
                                        <td>
                                            {{ $transaction->payment_type }}
                                        </td>
                                        <td>
                                            @if ($transaction->status == 'pending')
                                                <span href="#" class="badge badge-pill badge-default">{{ $transaction->status }}</span>
                                            @elseif ($transaction->status == 'failed')
                                                <span href="#" class="badge badge-pill badge-warning">{{ $transaction->status }}</span>
                                            @elseif ($transaction->status == 'success')
                                                <span href="#" class="badge badge-pill badge-success">{{ $transaction->status }}</span>
                                            @else
                                                <span href="#" class="badge badge-pill badge-danger">{{ $transaction->status }}</span>    
                                            @endif
                                        </td>
                                        <td>
                                            {{ dateID($transaction->created_at) }}
                                        </td>
                                        <td>
                                            {{ $transaction->player->email }}
                                        </td>
                                        {{-- <td class="text-center">
                                            @can('topup_charges.edit')
                                                <a href="{{ route('admin.transaction.edit', $transaction->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-pencil-alt"></i>
                                                </a>
                                            @endcan
                                        </td> --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No Transaction Found!</td>
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
        <div class="modal-body">Silahkan pilih "Delete" di bawah untuk detele crown package</div>
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
                        url: "{{ route("admin.crown_package.index") }}/"+idTemp,
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