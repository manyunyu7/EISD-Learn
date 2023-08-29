@if(session() -> has('success'))
    @push("script")
        <script>
            swal("Success!", "{{Session::get('success')}}", "success")
        </script>
    @endpush

    <div class="alert alert-success alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
             fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <polygon
                points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
        <strong>Success!</strong> {{Session::get( 'success' )}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        </button>
    </div>

@endif

@if(is_string($errors))

    @push("script")
        <script>
            SweetAlert.fire({
                title: 'Error!',
                text: '{!! $errors !!}',
                type: 'error'
            });
        </script>
    @endpush
    <div class="alert alert-danger alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
             fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <polygon
                points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
        <strong>Error!</strong> {!! $errors !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        </button>
        @elseif($errors->any())

            @push("script")
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: '{!! implode('', $errors->all('<div>:message</div>')) !!}',
                        type: 'error'
                    });
                </script>

            @endpush

            <div class="alert alert-danger alert-dismissible fade show">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                     fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <polygon
                        points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <strong>Error!</strong> {!! implode('', $errors->all('<div>:message</div>')) !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                </button>
            </div>
@endif


