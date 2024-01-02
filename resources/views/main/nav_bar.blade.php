<nav class="navbar navbar-header navbar-expand-lg" style="background-color: #ffffff">

    <div class="container-fluid">
        @auth

        <div class="text">
            <small id="greeting" style="color: rgb(112, 112, 112)">Good Morning,</small>
            <script>
                function updateGreeting() {
                    var currentTime = new Date();
                    var currentHour = currentTime.getHours();

                    var greetingElement = document.getElementById('greeting');

                    if (currentHour >= 5 && currentHour < 12) {
                        greetingElement.textContent = 'Good Morning,';
                    } else if (currentHour >= 12 && currentHour < 18) {
                        greetingElement.textContent = 'Good Afternoon,';
                    } else {
                        greetingElement.textContent = 'Good Evening,';
                    }
                }

                // Update greeting initially
                updateGreeting();

                // Update greeting every minute (adjust the interval as needed)
                setInterval(updateGreeting, 60000);
            </script>

            <h4 style="color: black"><b>{{ Auth::user()->name }}!</b></h4>
        </div>
        <div class="collapse ml-md-auto align-items-right" id="search-nav">
            <form class="navbar-right navbar-form nav-search mr-md-5 d-flex">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="submit" class="btn btn-search pr-1">
                            <i class="fa fa-search search-icon"></i>
                        </button>
                    </div>
                    <input style="width: 10px" type="text" placeholder="Search ..." class="form-control">
                </div>
            </form>
        </div>
        
        <ul class="navbar-nav topbar-nav ml-md-auto align-items-center hidden">
            <li class="nav-item dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        <img 
                            src="{{ Storage::url('public/profile/') . Auth::user()->profile_url }}" 
                            alt="..." 
                            class="avatar-img rounded-circle" 
                            onerror="this.onerror=null; this.src='{{ url('/default/default_profile.png') }}'; this.alt='Alternative Image';"
                        >
                    </div> 
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg"><img src="{{ Storage::url('public/profile/') . Auth::user()->profile_url }}" alt="image profile" class="avatar-img rounded"></div>
                                <div class="u-text">
                                    <h4>{{ Auth::user()->name }}</h4>
                                    <p class="text-muted">{{ Auth::user()->email }}</p><a href="/" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/profile') }}">Account Setting</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                      document.getElementById('logout-form').submit();">
                                <span class="link-collapse">Logout</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </div>
                </ul>
            </li>

        </ul>

        @endauth
    </div>
</nav>
