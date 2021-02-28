@extends('layouts.auth', ['title' => 'Login'])

@section('content')


<div class="container mt--8">
    <div class="row justify-content-center">

    <!-- Outer Row -->

        <div class="col-md-4 ">
            <div class="card o-hidden border-0 shadow-lg mb-3">
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="text-center">
                        <h1 class="h5 text-gray-900 mb-3">CONFIRM PASSWORD</h1>
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="form-group">
                            <label class="text-uppercase">Password</label>
                            <input id="password" type="password" class="form-control" name="password" tabindex="1">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                CONFIRM PASSWORD
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection