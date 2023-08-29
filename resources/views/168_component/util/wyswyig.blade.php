@push("css")
    <link rel="stylesheet" href="{{ asset('/168_res') }}/vendor/summernote/summernote-lite.min.css">
@endpush

@push("script")

    <script src="{{ asset('/168_res') }}/vendor/summernote/summernote-lite.min.js"></script>
    <script>
        var hoste = "http://127.0.0.1:2612/summernote-image";

        function initializeSummernote(id) {
            $('#' + id).summernote({
                tabsize: 2,
                height: 300,
                callbacks: {
                    onImageUpload: function (files) {
                        sendFile(files[0], id);
                    },
                    onMediaDelete: function (target) {
                        deleteFile(target[0].src);
                    }
                }
            });
        }

        function deleteFile(src) {
            var host = window.location.origin;
            $.ajax({
                data: {
                    "_token": "{{ csrf_token() }}",
                    src: src
                },
                type: "POST",
                url: host + "/summernote-image-delete",
                cache: false,
                success: function (resp) {
                    console.log(resp);
                    console.log("Success Delete Image")
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    let error = (textStatus + " " + errorThrown);
                    console.log(error)
                    alert(error + jqXHR.responseText)
                }
            });
        }

        function sendFile(file, id) {
            data = new FormData();
            data.append("file", file);
            $.ajax({
                data: data,
                type: 'POST',
                url: hoste,
                cache: false,
                contentType: false,
                processData: false,
                success: function (url) {
                    alert(url)
                    var image = $('<img>').attr('src', url);
                    $('#' + id).summernote('insertImage', url, url);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    let error = (textStatus + " " + errorThrown);
                    console.log(error)
                    alert(error + jqXHR.responseText)
                }
            });
        }

        // Initialize Summernote for elements with the following ids
        initializeSummernote('summernote');
        initializeSummernote('summernote2');
        initializeSummernote('summernote3');
        initializeSummernote('summernote4');
    </script>
@endpush
