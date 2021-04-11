@extends('errors.template')
@section('body_error')
<div class="row h-100">
    <div class="col-sm-12 my-auto">
        <!-- 404 Error Text -->
        <div class="text-center">
            <div class="error mx-auto" data-text="404">404</div>
            <p class="lead text-gray-800 mb-5">Kamu Tersesat</p>
            <strong>{{ $exception->getMessage() }}</strong>
            <p class="text-gray-500 mb-0">Tampaknya kamu tersesat , yuk kutuntun kembali </p>
            <h3>Feylacourse.xyz</h3>
            <a href="{{url('/')}}">&larr; Back to Home</a>
        </div>
    </div>
</div>
@endsection

 