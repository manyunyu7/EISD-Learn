<li class="nav-item {{ Request::is('home/*') ? 'active' : '' }}" style="display: flex; justify-content: center;">
        <a href="{{ url('/home') }}" style="display: flex; align-items: center;">
        <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/ic_home.svg"
             class="nav-ok-logo"
             style="width: 14%;
                    height: auto;
                    margin-top: 5px;
                    margin-right: 10px;"
        >
        <p class="{{ Request::is('home/*') ? 'text-white' : ''}}"
           style="margin-left: 10px; {{ (Request::is('home')) ? 'color: white !important;' : '' }}">Home</p>
    </a>
</li>


<li class="nav-item {{ (Request::is('class/*')) ? 'active' : ''}}">
    <a data-toggle="collapse" href="#base" style="display: flex; align-items: center;">
        <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/class.svg"
             class="nav-ok-logo"
             style="width: 14%;
                    height: auto;
                    margin-top: 5px;
                    margin-right: 10px;"
        >
        <p style="margin-left: 10px; {{ (Request::is('class/*')) ? 'color: white !important;' : '' }}"
           class="{{ (Request::is('class/*')) ? 'text-white active' : '' }}">Class</p>
        <span class="caret"></span>
    </a>
    <div class="collapse  {{ (Request::is('class/*')) ? 'show' : ''}}" id="base">
        <ul class="nav nav-collapse">
            <li>
                <a href="{{ url('/class/class-list') }}" style="display: flex; align-items: center;">
                    <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/classList.svg"
                         style="width: 14%;
                            height: auto;
                            margin-top: 5px;">
                    <p class="{{ Request::is('class/class-list') ? 'text-white' : ''}}" style="margin-left: 10px;">
                        Class List</p>
                </a>
            </li>
            <li>
                <a href="{{ url('/class/my-class') }}" style="display: flex; align-items: center;">
                    <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/myClass.svg"
                         style="width: 14%;
                            height: auto;
                            margin-top: 5px;">
                    <p class="{{ Request::is('class/my-class') ? 'text-white' : ''}}" style="margin-left: 10px;">
                        My Class</p>
                </a>
            </li>
        </ul>
    </div>
</li>

