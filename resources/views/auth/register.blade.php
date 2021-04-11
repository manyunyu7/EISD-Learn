@extends('auth.template')

@section('body')
    <div class="container">
        <div class="row h-100">
            <div class="col-sm-12 my-auto">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header w-100 center d-none">
                                <h2  class="gloss mt-2" style=" text-align:center; color: #7F00FF !important">Feylacourse</h2>
                                {{ __('Register') }}</div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <h4 class="gloss" style="color: #7F00FF" >Feylacourse</h4>
                                    <h4>Buat Akun</h4>
                                    <div class="form-group row">
                                        <label for="name"
                                            class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ old('name') }}" required autocomplete="name" autofocus>

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email"
                                            class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password"
                                            class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                required autocomplete="new-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm"
                                            class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control"
                                                name="password_confirmation" required autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-md-4 col-form-label text-md-right">Asal Institusi</label>

                                        <div class="col-md-6">
                                            <input type="text" class="form-control"
                                                name="institute" placeholder="Asal Institusi">
                                      <small id="helpId" class="form-text text-muted">Misal : Santri di santrenkoding</small>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label 
                                            class="col-md-4 col-form-label text-md-right">Profesi</label>

                                        <div class="col-md-6">
                                            <input type="text" class="form-control"
                                                name="jobs">
                                      <small id="helpId" class="form-text text-muted">Misal : Mobile Developer di PT XYZ atau <br> Mahasiswa di Telkom University</small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label 
                                            class="col-md-4 col-form-label text-md-right">Motto Hidup</label>

                                        <div class="col-md-6">
                                            <input type="text" class="form-control"
                                                name="motto">
                                      <small id="helpId" class="form-text text-muted">Misal : Man Jadda Wajada</small>
                                        </div>
                                    </div>


                                    
                                    <div class="form-group row">
                                        <label for="rolez"
                                            class="col-md-4 col-form-label text-md-right">Disini Aku Mau</label>

                                        <div class="col-md-6">
                                              <select class="form-control" name="rolez" id="">
                                                <option value="student">Belajar</option>
                                                <option value="mentor">Mengajar</option>
                                              </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Register') }}
                                            </button>
                                        </div>
                                    </div>
                                    <a class="btn btn-link" href="{{ url('login') }}">
                                        {{ __('Sudah Punya Akun ? Login Disini') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
