<li class="nav-item">
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
            <li class="{{ Request::is('lesson/create') ? 'active' : '' }}">
                <a href="{{ url('/lesson/create') }}">
                    <span class="sub-item">Buat Kelas</span>
                </a>
            </li>
        </ul>
    </div>
</li>

<li class="nav-item">
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
            <li class="{{ Request::is('/exam/n') ? 'active' : '' }}">
                <a href="{{ url('/exam/new') }}">
                    <span class="sub-item">Manage Exam</span>
                </a>
            </li>
            <li class="{{ Request::is('blog/create') ? 'active' : '' }}">
                <a href="{{ url('/blog/create') }}">
                    <span class="sub-item">Tulis Blog</span>
                </a>
            </li>
        </ul>
    </div>
</li>

<li class="nav-item">
    <a data-toggle="collapse" href="#base">
        <i class="fas fa-pen-alt"></i>
        <p>Blogs</p>
        <span class="caret"></span>
    </a>
    <div class="collapse  {{ Request::is('blog/*') ? 'show' : '' }}" id="base">
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
</li>
<li class="nav-item {{ Request::is('lesson/correct') ? 'active' : '' }}">
    <a href="{{ url('/lesson/correct') }}">
        <i class="fas fa-tasks"></i>
        <p>Tugas Akhir Siswa</p>
    </a>
</li>

