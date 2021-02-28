@extends('layouts.app', ['title' => 'User Management', 'subTitle' => 'Permissions', 'link' => 'admin.permission.index'])

@section('content')
<div class="row" style="min-height: 75vh">
    <section class="col-12">

        <div class="section-body">

            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-key"></i> Permissions</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">PERMISSION NAME</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($permissions as $no => $permission)
                                <tr>
                                    <td>{{ $permission->name }}</td>
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

@endsection