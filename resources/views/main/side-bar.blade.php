<div class="sidebar sidebar-style-2" style="background-color: #1D2026">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-dark">
                @if (Auth::user()->role == 'student')
                    @include('main.sidebar.student-sidebar')
                @else
                    @include('main.sidebar.mentor-sidebar')
                @endif

                    <li class="nav-item {{ Request::is('profile') ? 'active' : '' }}" style="display: flex; justify-content: center;">
                        <a href="{{ url('/profile') }}" style="display: flex; align-items: center;">
                            <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/myProfile.svg"
                                 style="width: 14%;
                                height: auto;
                                margin-top: 5px;
                                "
                            >
                            <p class="{{ Request::is('profile') ? 'text-white' : ''}}"
                               style="margin-left: 10px; {{ (Request::is('profile')) ? 'color: white !important;' : '' }}">My Profile</p>
                        </a>
                    </li>


                    <li class="nav-item {{ Request::is('home/*') ? 'active' : '' }}" style="display: flex; justify-content: center; margin-top: 300px">
                        <a href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           style="display: flex; align-items: center;">
                            <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/SignOut.svg"
                                 style="width: 14%;
                                height: auto;
                                margin-top: 5px;
                                "
                            >
                            <p class="{{ Request::is('home/*') ? 'text-white' : ''}}"
                               style="margin-left: 10px; {{ (Request::is('home')) ? 'color: white !important;' : '' }}">Logout</p>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </a>
                    </li>

            </ul>
        </div>
    </div>
</div>
