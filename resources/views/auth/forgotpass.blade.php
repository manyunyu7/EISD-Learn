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
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
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
                                            autofocus
                                            
                                        >
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 ">
                                        <button type="submit" class="btn btn-danger btn-block ">Submit</button>
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



