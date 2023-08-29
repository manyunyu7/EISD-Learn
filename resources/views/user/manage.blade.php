@extends('168_template')


@section("header_name")
    Pengguna
@endsection

@push('css')
    <!-- Datatable -->
    <link href="{{asset('/168_res')}}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@push('css_content')
        .buttons-columnVisibility {
            font-family: Nunito, sans-serif;
            font-size: medium;
            font-style: normal;
            border-radius: 20px;
            padding-left: 3px;
            padding-right: 3px;
            font-size: larger;
            font-weight: bold;
        }

        .buttons-columnVisibility.active{
            padding-right: 3px;
            font-weight: normal;
            padding-left: 3px;
            padding-right: 3px;
        }
@endpush

@push('script')
    <!-- Datatable -->
    <script type="text/javascript"
            src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.4/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/sr-1.1.1/datatables.min.js"></script>

    <script src="{{ asset('/168_js') }}/168_datatable.js"></script>


    <script src="{{ asset('/168_res') }}/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>
@endpush

@section("page_content")
    <div class="content-body" style="min-height: 798px;">
        <div class="container-fluid">
            <div class="row page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Pengguna</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">List</a></li>
                </ol>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">List Pengguna</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" style='font-family: Nunito, sans-serif '>
                                <table id="168dt" class="display" style="min-width: 845px">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th></th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Didaftarkan Pada</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($datas as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>
                                                <div class="chat-img d-inline-block">
                                                    <img src="{{asset($user->photo)}}" class="rounded-circle" width="45"
                                                         height="45" onerror="this.src='http://feylabs.my.id/fm/mdln_asset/mdln.png'">
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->contact }}</td>
                                            <td>{{ $user->created_at }}</td>
                                            <td>
                                                <div class="dropdown ms-auto text-end">
                                                    <div class="btn-link" data-bs-toggle="dropdown"
                                                         aria-expanded="false">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                             version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                               fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                                <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                                <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                                <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <a class="dropdown-item" href="{{url("user/$user->id/edit")}}">View Details</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    @empty

                                    @endforelse
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
