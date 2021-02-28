@extends('layouts.app', ['title' => 'Match Making Rate', 'subTitle' => 'Edit MMR | ' . $mmr->name, 'link' => 'admin.mmr.index'])

@section('content')

<div class="main-content" style="min-height: 75vh">
    <section class="section">

        <div class="section-body">

            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-unlock"></i> Edit Match Making Rate</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.mmr.update', $mmr->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>NAME</label>
                            <input type="text" name="name" value="{{ old('name', $mmr->name) }}" placeholder="Input MMR Name"
                                class="form-control @error('name') is-invalid @enderror">

                            @error('name')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>MIN RATE</label>
                            <input type="number" name="min_rate" value="{{ old('min_rate', $mmr->min_rate) }}" placeholder="Input Min Rate"
                                class="form-control @error('min_rate') is-invalid @enderror">

                            @error('min_rate')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>MAX RATE</label>
                            <input type="number" name="max_rate" value="{{ old('max_rate', $mmr->max_rate) }}" placeholder="Input Max Rate"
                                class="form-control @error('max_rate') is-invalid @enderror">

                            @error('max_rate')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>IMAGE</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">

                            @error('image')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i>
                            SIMPAN</button>
                        <button class="btn btn-warning btn-reset" type="reset"><i class="fa fa-redo"></i> RESET</button>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection