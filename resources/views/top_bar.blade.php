<nav class="navbar navbar-expand-sm navbar-custom navbar-lights ticky-top ">
    <a class="navbar-brand" href="#">
        <img width="300px"  src="{{URL::to('/')}}/home_assets/img/esd_3.png" alt="">
      </a>
    <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#collapsibleNavId"
        aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavId">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active">
                <a class="nav-link" href="{{url('/')}}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{url('/classes')}}">Kelas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"  href="{{url('/blogs')}}">Blog</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{url('/portfolios')}}">Portfolio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Podcast</a>
            </li>

            <li class="nav-item dropdownv d-none">
                <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">Dropdown</a>
                <div class="dropdown-menu" aria-labelledby="dropdownId">
                    <a class="dropdown-item" href="#">Action 1</a>
                    <a class="dropdown-item" href="#">Action 2</a>
                </div>
            </li>
        </ul>

        <ul class="navbar-nav  my-2 my-lg-0">
            @guest
                @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('login') }}">
                             <button type="button"
                                class="btn btn-outline-primary">Login</button>
                                <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('register') }}"> <button type="button"
                                class="btn btn-outline-primary">Register</button> <span
                                class="sr-only">(current)</span></a>
                    </li>
                @endif
            @else

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/home') }}"> <button type="button"
                            class="btn btn-outline-primary">Dashboard</button> <span
                            class="sr-only">(current)</span></a>
                </li>
            @endguest
        </ul>
    </div>
    <hr>
</nav>

