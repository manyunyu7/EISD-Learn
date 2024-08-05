{{-- TEMPLATE LAMA --}}
{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}


{{-- TEMPLATE BARU --}}
@extends('auth.template')
@section('body')
    <div class="row h-100">
        <div class="col-sm-12 my-auto">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card" style="border-radius: 30px;">
                        <div class="mt-4">
                            <div>
                                <h1 style="text-align: center"><b>Forgot Password</b></h1>
                            </div>
                        </div>

                        <div class="card-body d-flex justify-content-center align-items-center mb-5">
                            <form method="POST" action="{{ route('toEmail.linkForm') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input 
                                        type="email" 
                                        class="form-control custom-width @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email"
                                        value="{{ old('email') }}" 
                                        required 
                                        autocomplete="email" 
                                        autofocus
                                    >
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-danger btn-block">Submit</button>
                                </div>
                                <div class="mb-3 d-flex justify-content-end">
                                    <a class="btn btn-link" href="{{ url('login') }}" style="color: #FF1D01;">
                                        {{ __('Back to Login?') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><br>
            <div class="col-md-12 centere" style="display: flex; justify-content: center;">
                <img 
                    src="{{URL::to('/')}}/home_assets/img/ic_LearningMDLN.svg" 
                    style=" width:35%; 
                            height: auto;
                            display: flex;
                            align-items: center;
                            "
                >
            </div>
        </div>
    </div>
@endsection
