@php
    $numStudents = DB::select(
        "SELECT *
                        FROM
                            student_lesson a
                        WHERE a.lesson_id = $data->id",
    );
    $numStudentsCount = count($numStudents);
@endphp

<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
    <div class="card shadow ">
        <!-- Image -->
        <img class="card-img-top" style="aspect-ratio: 16 / 9"
            onerror="this.onerror=null; this.src='{{ url('/default/default_courses.jpeg') }}'; this.alt='Course Image';"
            src="{{ env('AWS_BASE_URL') . $data->course_cover_image }}" alt="La Noyee">
        <!-- Card body -->
        <div class="card-body">
            <!-- Badge and favorite -->
            <div
                style="width: 100%; display: flex; flex-wrap: wrap; justify-content: left; align-items: flex-start; margin-bottom: .5rem;">
                @if ($data->new_class == 'Aktif')
                    <div class="class-badge"
                        style="color: white; margin-bottom: 5px; margin-right: 10px; background-color: rgb(31, 65, 151); padding: 2px 10px;">
                        NEW
                    </div>
                @endif
                <div class="class-badge"
                    style="color: white; margin-bottom: 5px; margin-right: 5px; background-color: {{ $data->course_category_color }}; padding: 2px 10px;">
                    <strong>{{ $data->course_category_name }}</strong>
                </div>
                <div class="class-badge"
                    style="color: black; display: flex; align-items: center; margin-bottom: 5px; margin-left: auto;">
                    <img src="{{ url('/icons/star.svg') }}" style="margin-right: 4px;">
                    <p style="font-size: 15px; margin-bottom: 0;"><strong>5.0</strong></p>
                </div>
            </div>

            <!-- Title -->
            <h6 class="card-title"><a href="#">{{ $data->course_title }}</a></h6>
            <p class="mb-2 text-truncate-2 d-none">Proposal indulged no do sociable he throwing
                settling.</p>


            <hr style="margin-left: -20px; margin-right: -20px" class="mb-3 mt-2">

            <li class="toga-container dropdown hidden-caret"
                style="display: flex; justify-content: space-between; align-items: center;">
                <img style="width: 15%; height: auto; max-height: 20px; order: 1;"
                    src="{{ url('/home_icons/Toga_MDLNTraining.svg') }}">
                <p style="font-size: 15px; margin-bottom: 3px; order: 2; flex-grow: 1; text-align: center;">
                    {{ $data->mentor_name }}</p>
                <div style="order: 3;">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <img id="dotsThree" src="{{ url('/home_icons/DotsThree.svg') }}" alt="">
                    </a>

                    <!-- Modal -->
                    <div class="modal fade" id="inputPinModal{{ $data->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" action="{{ url('/input-pin') }}">
                                {{-- cek Token CSRF --}}
                                @csrf
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h1 class="modal-title" id="exampleModalLabel"><b>Masukan
                                                PIN</b></h1>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body center" style="justify-content: center">
                                        <p>Untuk masuk ke dalam kelas, silakan masukan PIN
                                            terlebih dahulu</p>
                                        <div class="mb-3">
                                            <!-- Hidden Input -->
                                            <input type="hidden" id="hiddenField" name="idClass"
                                                value='{{ $data->id }}'>
                                            <!-- PIN Input -->
                                            <input name="pin" style="border: 1px solid #ced4da;"
                                                class="form-control" type="text" id="pin" required
                                                placeholder="Masukan PIN disini">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel
                                        </button>
                                        <button type="submit" class="btn " style="background-color: #208DBB"><span
                                                style="color: white">Submit</span>
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>


                    <ul class="dropdown-menu dropdown-user animated fadeIn" style="right: 0; left: auto;">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <a class="dropdown-item" href="#" data-toggle="modal"
                                    data-target="#inputPinModal{{ $data->id }}">Join Class</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ url('/class/class-list/view-class/' . $data->id) }}">
                                    <span class="link-collapse">View Class</span>
                                </a>
                            </li>
                        </div>
                    </ul>
                </div>
                <!-- Modal -->
                <form method="POST" action="{{ url('/input-pin') }}" style="order: 0;">
                    {{-- cek Token CSRF --}}
                    @csrf
                    <div class="modal fade" id="joinClassModal{{ $data->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title" id="exampleModalLabel"><b>Masukan PIN</b></h1>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body center" style="justify-content: center">
                                    <p>Untuk masuk ke dalam kelas, silakan masukan PIN terlebih dahulu</p>
                                    <div class="mb-3">
                                        <!-- Hidden Input -->
                                        <input type="hidden" id="hiddenField" name="idClass"
                                            value='{{ $data->id }}'>
                                        <!-- PIN Input -->
                                        <input name="pin" style="border: 1px solid #ced4da;" class="form-control"
                                            type="text" id="pin" required placeholder="Masukan PIN disini">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn" style="background-color: #208DBB"><span
                                            style="color: white">Submit</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </li>

            <!-- Rating star -->
            <ul class="list-inline mb-0 d-none">
                <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i>
                </li>
                <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i>
                </li>
                <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i>
                </li>
                <li class="list-inline-item me-0 small"><i class="fas fa-star text-warning"></i>
                </li>
                <li class="list-inline-item me-0 small"><i class="far fa-star text-warning"></i>
                </li>
                <li class="list-inline-item ms-2 h6 fw-light mb-0">4.0/5.0</li>
            </ul>
        </div>
        <!-- Card footer -->
        <div class="card-footer pt-0 pb-3">
            <div style="display: flex; justify-content: center; align-items: center;">
                <!-- Icon for students -->
                <img style="width: 10%; height: auto; margin-top: 12px;"
                    src="{{ url('/icons/UserStudent_mentor.svg') }}">

                <!-- Link to view students -->
                {{-- href="{{ url('/class/class-list/students/' . $data->id) }}" --}}
                <a style="text-decoration: none; color: black;">
                    <p style="font-size: 17px; margin-left: 10px; margin-top: 28px;">
                        <b>{{ $numStudentsCount }}</b><span style="color: #8C94A3;"> students</span>
                    </p>
                </a>


            </div>
        </div>

    </div>
</div>
