{{-- IMPROVED MENU --}}
{{-- HOME --}}
<li class="nav-item {{ Request::is('home*') ? 'active' : '' }}" style="display: flex; justify-content: center;">
    <a href="{{ url('/home') }}" style="display: flex; align-items: center;">
        <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/ic_home.svg"
             class="nav-ok-logo"
             style="width: 14%;
                    height: auto;
                    margin-top: 5px;
                    "
        >
        <p class="{{ Request::is('home/*') ? 'text-white active' : ''}}"
           style="margin-left: 10px; {{ (Request::is('home*')) ? 'color: white !important;' : '' }}">Home</p>
    </a>
</li>

{{-- CLASS --}}
<li class="nav-item {{ Request::is('lesson/*') ? 'active' : '' }}" style="display: flex; justify-content: center;">
    <a href="{{ url('lesson/manage_v2') }}" style="display: flex; align-items: center;">
        <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/class.svg"
             class="nav-ok-logo"
             style="width: 14%;
                    height: auto;
                    margin-top: 5px;
                    margin-right: 10px;"
        >
        <p style="margin: 0; {{ (Request::is('lesson/*')) ? 'color: white !important;' : '' }}"
           class="{{ (Request::is('lesson/*')) ? 'text-white active' : '' }}">Class</p>
    </a>
</li>

{{-- EXAM --}}
<li class="nav-item {{ Request::is('exam/*') ? 'active' : '' }}" style="display: flex; justify-content: center;">
    <a href="{{ url('exam/manage-exam-v2') }}" style="display: flex; align-items: center;">
        <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/Exam.svg"
             class="nav-ok-logo"
             style="width: 14%;
                height: auto;
                min-width: 14%!important;
                margin-top: 5px;
                "
        >
        <p class="{{ Request::is('exam/*') ? 'text-white' : ''}}"
           style="margin-left: 10px; {{ (Request::is('exam/*')) ? 'color: white !important;' : '' }}">Exam New</p>
    </a>
</li>

{{-- DASHBOARD --}}
<li class="nav-item {{ Request::is('dashboard/*') ? 'active' : '' }}" style="display: flex; justify-content: center;">
    <a href="{{ url('/dashboard/mentor') }}"  style="display: flex; align-items: center;">
        <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/Dashboard.svg"
             class="nav-ok-logo"
             style="width: 14%;
                height: auto;
                margin-top: 5px;
                "
        >
        <p class="{{ Request::is('dashboard/*') ? 'text-white' : ''}}"
           style="{{ Request::is('dashboard/*') ? 'color: white !important;' : '' }} margin-left: 10px;">Dashboard</p>
    </a>
</li>


{{-- <hr>
<p class="text-center">MENU LAMA</p> --}}
{{-- MENU LAMA --}}
{{-- CLASS --}}
{{-- <li class="nav-item">
    <a data-toggle="collapse" href="#lesson">
        <i class="fas fas fa-book"></i>
        <p>Kelas</p>
        <span class="caret"></span>
    </a>
    <div class="collapse  {{ Request::is('lesson/*') ? 'show' : '' }}" id="lesson">
        <ul class="nav nav-collapse">
            <li class="{{ Request::is('lesson/manage') ? 'active' : '' }}">
                <a href="{{ url('/lesson/manage') }}">
                    <span class="sub-item">Manage Kelas</span>
                </a>
            </li>
            <li class="{{ Request::is('lesson/category') ? 'active' : '' }}">
                <a href="{{ url('/lesson/category') }}">
                    <span class="sub-item">Class Category</span>
                </a>
            </li>
            <li class="{{ Request::is('lesson/create') ? 'active' : '' }}">
                <a href="{{ url('/lesson/create') }}">
                    <span class="sub-item">Buat Kelas</span>
                </a>
            </li>
        </ul>
    </div>
</li> --}}

{{-- EXAM --}}
{{-- <li class="nav-item">
    <a data-toggle="collapse" href="#exam">
        <i class="fas fa-pen-square"></i>
        <p>Exam</p>
        <span class="caret"></span>
    </a>
    <div class="collapse  {{ Request::is('exam/*') ? 'show' : '' }}" id="exam">
        <ul class="nav nav-collapse">
            <li class="{{ Request::is('/exam/new') ? 'active' : '' }}">
                <a href="{{ url('/exam/new') }}">
                    <span class="sub-item">Tambah Exam Baru</span>
                </a>
            </li>
            <li class="{{ Request::is('/exam/manage') ? 'active' : '' }}">
                <a href="{{ url('/exam/manage') }}">
                    <span class="sub-item">Manage Exam</span>
                </a>
            </li>
        </ul>
    </div>
</li> --}}

{{-- BLOGS --}}
{{-- <li class="nav-item {{ Request::is('blog/*') ? 'active' : '' }}">
    <a data-toggle="collapse" href="#base">
        <i class="fas fa-pen-alt"></i>
        <p>Blogs</p>
        <span class="caret"></span>
    </a>
    <div class="collapse {{ Request::is('blog/*') ? 'show' : '' }}" id="base">
        <ul class="nav nav-collapse">
            <li>
                <a href="{{ url('/blogs') }}">
                    <span class="sub-item">Lihat Blog</span>
                </a>
            </li>
            <li class="{{ Request::is('blog/manage') ? 'active' : '' }}">
                <a href="{{ url('/blog/manage') }}">
                    <span class="sub-item">Manage Blog</span>
                </a>
            </li>
            <li class="{{ Request::is('blog/create') ? 'active' : '' }}">
                <a href="{{ url('/blog/create') }}">
                    <span class="sub-item">Tulis Blog</span>
                </a>
            </li>
        </ul>
    </div>
</li> --}}

{{-- TUGAS AKHIR SISWA --}}
{{-- <li class="nav-item {{ Request::is('lesson/correct') ? 'active' : '' }}">
    <a href="{{ url('/lesson/correct') }}">
        <i class="fas fa-tasks"></i>
        <p>Tugas Akhir Siswa</p>
    </a>
</li> --}}
