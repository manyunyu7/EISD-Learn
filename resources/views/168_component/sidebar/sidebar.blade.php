<div class="dlabnav">
    <div class="dlabnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="dropdown header-profile">
                @php
                $photoUrl = "user/".Auth::id()."/edit";
                @endphp

                <a class="nav-link" href="javascript:void(0)" role="button" data-bs-toggle="dropdown">
                    <img src="{{Auth::user()->photo_path}}" width="20"
                         onerror="this.src='http://feylabs.my.id/fm/mdln_asset/mdln.png'">
                    <div class="header-info ms-3">
                        <span class="font-w600 ">Hi,<b>{{Auth::user()->name}}</b></span>
                        <small class="text-end font-w400"></small>
                    </div>
                </a>

                <div class="dropdown-menu dropdown-menu-end">
                    <a href='{{url("$photoUrl")}}' class="dropdown-item ai-icon">
                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span class="ms-2">Profile </span>
                    </a>
{{--                    <a href="{{ asset('/frontend') }}email-inbox.html" class="dropdown-item ai-icon">--}}
{{--                        <svg id="icon-inbox" xmlns="http://www.w3.org/2000/svg" class="text-success" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>--}}
{{--                            <polyline points="22,6 12,13 2,6"></polyline>--}}
{{--                        </svg>--}}
{{--                        <span class="ms-2">Inbox </span>--}}
{{--                    </a>--}}
                    <a href="{{ url('/logout') }}" class="dropdown-item ai-icon">
                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span class="ms-2">Logout </span>
                    </a>
                </div>
            </li>

            <li><a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
{{--                    <i class="flaticon-381-user-9"></i>--}}
                    <span class="nav-text">Pengguna</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ url("/user/create")}}">Tambah Pengguna</a></li>
                    <li><a href="{{ url("/user/manage") }}">Manage Pengguna</a></li>
                </ul>
            </li>

            <li><a class="has-arrow ai-icon d-none" href="javascript:void(0)" aria-expanded="false">
                    <span class="nav-text">Payment</span>
                </a>
                <ul aria-expanded="false">
                    <li class=""><a class="has-arrow" href="javascript:void(0)" aria-expanded="false">Bank/Wallet</a>
                        <ul aria-expanded="false" class="left mm-collapse" style="">
                            <li><a href="{{url("payment-merchant/tambah")}}">Tambah Baru</a></li>
                            <li><a href="{{url("payment-merchant/manage")}}">Manage</a></li>
                        </ul>
                    </li>

                    <li class=""><a class="has-arrow" href="javascript:void(0)" aria-expanded="false">Payment Account</a>
                        <ul aria-expanded="false" class="left mm-collapse" style="">
                            <li><a href="{{url("donation-account/tambah")}}">Tambah Baru</a></li>
                            <li><a href="{{url("donation-account/manage")}}">Manage</a></li>
                        </ul>
                    </li>
                </ul>
            </li>


            <li><a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
{{--                    <i class="flaticon-381-file-1"></i>--}}
                    <span class="nav-text">Content</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ url("/news/create")}}">Tambah Konten</a></li>
                    <li><a href="{{ url("/news/manage") }}">Manage Konten</a></li>
                </ul>
            </li>

            <li><a class="has-arrow ai-icon d-none" href="javascript:void(0)" aria-expanded="false">
{{--                    <i class="flaticon-381-file-1"></i>--}}
                    <span class="nav-text">Sodaqo</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ url("/sodaqo-category/manage")}}">Kategori</a></li>
                    <li><a href="{{ url("/sodaqo/me") }}">Program Saya</a></li>
                </ul>
            </li>

        </ul>
        <div class="copyright">
            <p class="fs-12">Made with <span class="heart"></span> by Sodaqo.id</p>
        </div>
    </div>
</div>
