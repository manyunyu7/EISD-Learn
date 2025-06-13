@extends('errors.template')
@section('body_error')
    <div class="row h-100">
        <div class="col-sm-12 my-auto">
            <!-- 401 Error Text -->
            <div class="text-center w-100">
                <div class="error mx-auto" data-text="401">401</div>
                {{-- <p class="my-4">
                    {{$message}}
                </p> --}}
                <p>
                    @if(Str::contains($message, 'Skor Lulus Minimum'))
                        Anda belum mencapai <strong>Skor Lulus Minimum</strong> pada Bagian <strong>{{ $sectionTitle }}</strong>. <br>Mohon selesaikan sesi tersebut dengan skor yang memenuhi.
                    @else
                        {!! nl2br(e($message)) !!} {{-- Menggunakan e() untuk escape, nl2br untuk baris baru --}}
                    @endif
                </p>
                <p class="lead text-gray-800">
                    Anda Tidak Punya Akses ke Halaman Ini
                </p>
                <p>
                    Lihat detail di: <a href="{{ $link }}">{{ $link }}</a>
                </p>
                {{-- <a href="{{ route('login') }}">&larr; Log In</a> --}}
            </div>
        </div>
    </div>
@endsection
