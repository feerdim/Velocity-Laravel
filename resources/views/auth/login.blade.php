@extends('layouts.auth', ['title' => 'Login'])

@section('content')

<div class="container mt--8 pb-5">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <div class="card bg-secondary border-0 mb-0">
          <div class="card-body px-lg-5 py-lg-5">
            <div class="text-center text-muted mb-4">
              <small>Sign in to access the admin dashboard</small>
            </div>
            <form action="{{ route('login') }}" method="POST" role="form">
                @csrf
                <div class="form-group mb-3 @error('email') has-danger @enderror">
                    <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" type="email" aria-label="email" aria-describedby="email">
                </div>
                @error('email')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>    
                @enderror
                </div>
                <div class="form-group @error('password') has-danger @enderror">
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                        </div>
                        <input name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror" placeholder="Password" type="password">
                    </div>
                    @error('password')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>    
                    @enderror
                </div>
                {{-- <div class="custom-control custom-control-alternative custom-checkbox">
                    <input class="custom-control-input" id=" customCheckLogin" type="checkbox">
                    <label class="custom-control-label" for=" customCheckLogin">
                    <span class="text-muted">Remember me</span>
                    </label>
                </div> --}}
                <div class="text-center">
                    <button type="submit" class="btn btn-primary my-4">Sign in</button>
                </div>
            </form>
          </div>
        </div>
        {{-- <div class="row mt-3">
          <div class="col-6">
            <a href="#" class="text-light"><small>Forgot password?</small></a>
          </div>
          <div class="col-6 text-right">
            <a href="#" class="text-light"><small>Create new account</small></a>
          </div>
        </div> --}}
      </div>
    </div>
</div>

@endsection