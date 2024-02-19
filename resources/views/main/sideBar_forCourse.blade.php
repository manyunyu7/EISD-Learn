<div class="sidebar sidebar-style-2" style="background-color: #1D2026">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-dark">
                <li class="nav-item">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                        <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/SignOut.svg" 
                        style="width: 14%; 
                            height: auto;
                            display: flex;
                            margin-top: 5px;
                            "
                        >
                        <p style="padding-left: 12px; margin-top: 13px; font-size: 15px;">Sign-out</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
