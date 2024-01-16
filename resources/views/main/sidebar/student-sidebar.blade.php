
<li class="nav-item {{ (Request::is('blog/*')) ? 'active' : ''}}">
    <a href={{url('/home')}}>
        <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/ic_home.svg" 
             style="width: 14%; 
                height: auto;
                display: flex;
                margin-top: 5px;
                "
        >
        <p style="padding-left: 12px; margin-top: 13px; font-size: 15px;">Home</p>
        
    </a>
</li>

<li class="nav-item  {{ (Request::is('portfolio/*')) ? 'active' : ''}}">
    <a data-toggle="collapse" href="#arts">
        <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/Group.svg" 
             style="width: 14%; 
                height: auto;
                display: flex;
                margin-top: 5px;
                "
        >
        <p style="padding-left: 12px; margin-top: 13px; font-size: 15px;">Class</p>
        <span class="caret"></span>
    </a>
    <div class="collapse  {{ (Request::is('portfolio/*')) ? 'show' : ''}}" id="arts">
        <ul class="nav nav-collapse">
            <li>
                <a href="{{url('/class-list')}}">
                    <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/classList.svg" 
                        style="width: 14%; 
                            height: auto;
                            display: flex;
                            margin-top: 5px;
                            "
                    >
                    <span style="padding-left: 12px; margin-top: 13px; font-size: 15px;">Class List</span>
                </a>
            </li>
            <li class="{{ (Request::is('/my-class')) ? 'active' : ''}}">
                <a href="{{url('/my-class')}}">
                    <img src="{{URL::to('/')}}/home_assets/img/Icon_Side_Bar/myClass.svg" 
                        style="width: 14%; 
                            height: auto;
                            display: flex;
                            margin-top: 5px;
                            "
                    >
                    <span style="padding-left: 12px; margin-top: 13px; font-size: 15px;">My Class</span>
                </a>
            </li>
        </ul>
    </div>
</li>
    