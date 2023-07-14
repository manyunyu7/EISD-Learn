<div class="sidebar sidebar-style-1">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            @auth


                <div class="user">
                    <div class="avatar-sm float-left mr-2f">
                        <img src="{{ Storage::url('public/profile/') . Auth::user()->profile_url }}" alt="profile"
                            class="avatar-img rounded-circle">
                    </div>
                    <div class="info">
                        <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                            <span>
                                {{ Auth::user()->name }}
                                <span style="text-transform: capitalize;" class="user-level">{{ Auth::user()->role }}</span>
                                <span class="caret"></span>
                            </span>
                        </a>
                        <div class="clearfix"></div>

                        <div class="collapse in" id="collapseExample">
                            <ul class="nav">
                                <li>
                                    <a href="{{ url('/profile') }}" class="active">
                                        <span class="link-collapse">My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                                      document.getElementById('logout-form').submit();">
                                        <span class="link-collapse">Logout</span>
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endauth
            <ul class="nav nav-danger">
                <hr>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">List Materi</h4>
                </li>
                <hr>
                <div class="container">

                    @forelse ($section as $item)
                        <li class="nav-item card p-1 bg-light" style="margin-bottom: 6px !important">
                            <a href="{{ route('course.see_section', [$item->lesson_id, $item->section_id]) }}">
                                <span class="badge badge-default">{{ $item->section_order }}</span><br>
                            </a>
                            <p class="text-dark" style="margin-bottom: 0px !important">{{ $item->section_title }}</p>
                            <div class="mt-1 mb-1">
                                @if (isset($item) && isset($item->isTaken))
                                    @php
                                        $isCurrent = $item->isCurrent ?? false;
                                    @endphp
                                    @if ($item->isTaken && !$isCurrent)
                                        <span class="badge badge-success text-small">Sudah Dipelajari</span>
                                    @elseif ($isCurrent)
                                        <span class="badge badge-primary text-small">Sedang Dipelajari</span>
                                    @else
                                        <span class="badge badge-secondary text-small">Belum Diambil</span>
                                    @endif
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="nav-item card p-1 bg-dark" style="margin-bottom: 6px !important">
                            {{-- <a href="{{ route('course.see_section', [$item->lesson_id, $item->section_id]) }}">
                        <span class="badge badge-success ">{{ $item->section_order }}</span><br>
                        </a> --}}
                            <p style="margin-bottom: 0px !important"> Belum Ada Materi di Kelas Ini</p>
                        </li>
                    @endforelse
                </div>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    @auth

                        @if ($isRegistered)
                    <li class="nav-item card p-2 border border-primary mx-2" style="margin-bottom: 6px !important">
                        <h4 style="margin-bottom: 0px !important; color:black">Project Akhir</h4>
                        <p style="margin-bottom: 0px !important" class=""> <small id="helpId"
                                class="form-text text-muted">Lihat Penugasan Pada Materi Project Akhir</small></p>
                        <hr>
                        <a href="{{ route('course.submission', [$lesson->id]) }}">
                            <button type="button" name="" id="" class="btn btn-primary btn-border">Buka
                                Halaman Project</button>
                        </a>
                        <small style="margin-bottom: 2px !important width:100% text-align:center"
                            class="text-danger">Dikerjakan
                            setelah semua materi yang ada diselesaikan</small>
                    </li>
                    @endif

                @endauth
                </li>
                <!-- <li class="mx-4 mt-2">
                        <a href="http://themekita.com/atlantis-bootstrap-dashboard.html" class="btn btn-primary btn-block">
                            <span class="btn-label mr-2"> <i class="fa fa-heart"></i> </span>Donasi Untuk Guru</a>
                    </li> -->
            </ul>
        </div>
    </div>
</div>
