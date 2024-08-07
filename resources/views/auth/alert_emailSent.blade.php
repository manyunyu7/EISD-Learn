<!-- resources/views/auth/password-sent.blade.php -->
@extends('auth.template')

@if (session('success'))
    @section('body')
        <div class="row h-100">
            <div class="col-sm-12 my-auto">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card" style="border-radius: 30px;">
                            <div class="mt-4">
                                <div>
                                    <h1 style="text-align: center"><b>Email Sent!</b></h1>
                                </div>
                            </div>

                            <div class="card-body d-flex justify-content-center align-items-center mb-7">
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="col-md-12 centere" style="display: flex; justify-content: center;">
                    <img src="{{URL::to('/')}}/home_assets/img/ic_LearningMDLN.svg" style="width:35%; height: auto; display: flex; align-items: center;">
                </div>
            </div>
        </div>
    @endsection
@endif
@section('body')
        <div class="row h-100">
            <div class="col-sm-12 my-auto">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card" style="border-radius: 30px;">
                            <div class="mt-4 mb-3">
                                <div>
                                    <h1 style="text-align: center" ><b><a href="{{ route('login') }}" style="text-decoration: none">Back to Login Pages</a></b></h1>
                                </div>
                            </div>

                        </div>
                    </div>
                </div><br>
                <div class="col-md-12 centere" style="display: flex; justify-content: center;">
                    <img src="{{URL::to('/')}}/home_assets/img/ic_LearningMDLN.svg" style="width:35%; height: auto; display: flex; align-items: center;">
                </div>
            </div>
        </div>
@endsection