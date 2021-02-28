@extends('layouts.app', ['title' => 'Crown Packages', 'subTitle' => 'Edit Package' , 'link' => 'admin.crown_package.index'])

@section('content')

<div class="main-content" style="min-height: 75vh">
    <section class="section">

        <div class="section-body">

            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-unlock"></i> Edit Crown Package</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.crown_package.update', $crown_package->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>PACKAGE NAME</label>
                            <input type="text" name="name" value="{{ old('name', $crown_package->name) }}" placeholder="Ex: 100"
                                class="form-control @error('name') is-invalid @enderror">

                            @error('name')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>PRICE</label>
                            <input type="number" name="price" value="{{ old('price', $crown_package->price) }}" placeholder="Ex: 15.000"
                                class="form-control @error('price') is-invalid @enderror">

                            @error('price')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i>
                            EDIT</button>
                        <button class="btn btn-warning btn-reset" type="reset"><i class="fa fa-redo"></i> RESET</button>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection