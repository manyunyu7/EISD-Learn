@extends('main.template_openClass')


@section('main')
<br><br>
    <div class="row mt--2 border-primary col-md-12">
      <!-- Yellow Container -->
      <div class="col-md-12 " >
        <div class="col-md-12" >
            <div class="col-md-12 mt-3 mb-5">
                
            </div>
            @if (session()->has('success'))
                <script>
                    toastr.success('{{ session('success') }}', '{{ Session::get('success') }}');
                </script>
            @elseif(session()->has('error'))
                <script>
                    toastr.error('{{ session('error') }}', '{{ Session::get('error') }}');
                </script>
            @endif
        </div>
    </div>
@endsection
