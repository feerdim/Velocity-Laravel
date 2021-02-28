@extends('layouts.app', ['title' => 'Games', 'subTitle' => 'Games | edit ' . $game->name, 'icon' => 'controller', 'link' => 'admin.game.index'])

@section('content')

<div class="main-content" style="min-height: 75vh">
    <section class="section">

        <div class="section-body">

            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-gamepad"></i> Edit Game</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.game.update', $game->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Game Name</label>
                            <input type="text" name="name" value="{{ old('name', $game->name) }}" placeholder="Input Game Name" class="form-control @error('name') is-invalid @enderror">

                            @error('name')
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

                        <div class="form-group">
                            <label>BACKGROUND</label>
                            <input type="file" name="background" class="form-control @error('background') is-invalid @enderror">

                            @error('background')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i> EDIT</button>
                        <button class="btn btn-warning btn-reset" type="reset"><i class="fa fa-redo"></i> RESET</button>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


@endsection

