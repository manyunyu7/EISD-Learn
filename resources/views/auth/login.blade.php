@extends('auth.template')
@section('body')
    <div class="row h-100">
        <div class="col-sm-12 my-auto">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card" style="border-radius: 30px;">

                        <div class="mt-4">

                        </div>

                        <div class="card-body  justify-content-center align-items-center mb-5">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <h3 class="form-label font-inter text-center">Login</h3>
                                    <label for="exampleInputEmail1" class="form-label">Username</label>
                                    <input
                                        type="email"
                                        class="username form-control custom-width @error('email') is-invalid @enderror"
                                        id="exampleInputEmail1"
                                        aria-describedby="emailHelp"
                                        placeholder="Username or Email Address"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="mb-3">

                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input
                                            type="password"
                                            class="password form-control @error('password') is-invalid @enderror"
                                            id="exampleInputPassword1"
                                            placeholder="Password"
                                            name="password"
                                            required
                                            autocomplete="current-password"
                                        >
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="mb-3 form-check d-flex justify-content-start">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                               checked>
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Remember me</label>
                                    </div>
                                </div>
                                <div class="mb-3 ">
                                    <button type="submit" class="btn btn-danger btn-block ">Submit</button>
                                </div>
                                <div class="mb-3 d-flex justify-content-end">
                                    <a class="btn btn-link link_forgotPwd" href="{{ url('forgotpass') }}"
                                       style="color: #FF1D01;">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <br>
            <div class="col-md-12 centere" style="display: flex; justify-content: center;">
                <img height="50"
                    src="{{URL::to('/')}}/home_assets/img/ic_LearningMDLN.svg"
                    style=" width:35%;
                                display: flex;
                                align-items: center;
                                "
                >
            </div>
        </div>
    </div>
@endsection



