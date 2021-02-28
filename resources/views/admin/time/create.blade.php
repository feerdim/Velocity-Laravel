@extends('layouts.app', ['title' => 'Schedule Management', 'subTitle' => 'Create Time' , 'link' => 'admin.time.index'])

@section('content')

<div class="row" style="min-height: 75vh">
    <section class="col-3">

        <div class="section-body">

            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-unlock"></i> Create Schedulte Time</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.time.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="time_start" class="form-control-label">Time</label>
                            <input name="start" value="{{ old('start') }}" class="form-control @error('start') is-invalid @enderror" type="time" id="time_start" step="3600">

                            @error('start')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>


                        <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i>
                            CREATE</button>
                        <button class="btn btn-warning btn-reset" type="reset"><i class="fa fa-redo"></i> RESET</button>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection