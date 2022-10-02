@extends('layouts.app')

@section('head')
    <link href="{{ asset('libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ subdirMix('css/config/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-default-stylesheet" />
    <link href="{{ subdirMix('css/units.css') }}" rel="stylesheet" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="{{ asset('libs/tippy.js/tippy.all.min.js') }}"></script>
@endsection

@section('content')
    <div class="container-fluid mt-2">
        <div class="col-12">
            <div class="card" style="margin-bottom: 6%">
                <div class="card-body">
                    <button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-end fs-5"
                        data-bs-toggle="modal" data-bs-target="#store-unit" data-toggle="tooltip">
                        <i class="mdi mdi-plus-circle pe-1"></i> Register
                    </button>
                    <h4 class="header-title mb-4">Units</h4>
                    <div class="table-responsive">
                        <table class="table table-hover m-0 table-cente#f1556c dt-responsive nowrap w-100"
                            id="datatable-buttons">
                            {{-- or tickets-table --}}
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Mobile #</th>
                                    <th>Address</th>
                                    <th>Last&nbsp;Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($units as $unit)
                                    <tr>
                                        <td> {{ $unit->id }} </td>
                                        <td id="{{ $unit->id }}.status" class="text-capitalize">
                                            {{ $unit->status }}
                                        </td>
                                        <td> {{ $unit->phone_number }} </td>
                                        <td id="{{ $unit->id }}.address"> {{ $unit->formatted_address }} </td>
                                        <td id="{{ $unit->id }}.updated_at">
                                            {{ \Carbon\Carbon::parse($unit->updated_at)->toDayDateTimeString() }}
                                        </td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <a href="" class="btn btn-light btn-sm" data-bs-toggle="dropdown"
                                                    aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item"
                                                        href="{{ route('unit.logs', ['id' => $unit->id]) }}"><i
                                                            class="mdi mdi-format-list-bulleted me-2 text-muted font-18 vertical-middle"></i>Logs</a>
                                                    <a id="refreshbtn[{{ $unit->id }}]"
                                                        onclick="refreshUnit({{ $unit->id }});" class="dropdown-item"
                                                        type="button"><i id="ref-icon[{{ $unit->id }}]"
                                                            class="fe-refresh-ccw text-muted me-2 font-18 vertical-middle"></i>Refresh</a>
                                                    <a id="delbtn" class="dropdown-item"
                                                        onclick="removeUnit({{ $unit->id }})" type="button"
                                                        data-bs-toggle="modal" data-bs-target="#modalRemove"><i
                                                            class="fe-trash text-muted me-2 font-18 vertical-middle"></i>Remove</a>
                                                </div>
                                            </div>
                                        </td>
                                        {{-- <td>
                                                <a href="{{ route('unit.logs', ['id' => $unit->id]) }}" type="button"
                                                    class="btn border-0 float-end p-0">
                                                    <i class="mdi mdi-format-list-bulleted fs-5" title="Logs" tabindex="0"
                                                        data-plugin="tippy" data-tippy-placement="top"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <button type="button" class="btn border-0 float-end p-0"
                                                    id="refreshbtn[{{ $unit->id }}]"
                                                    onclick="refreshUnit({{ $unit->id }});">
                                                    <i id="ref-icon[{{ $unit->id }}]"
                                                        class="fe-refresh-ccw text-success fs-5" title="Refresh"
                                                        tabindex="0" data-plugin="tippy" data-tippy-placement="top"
                                                        type="button"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button id="delbtn" class="btn border-0 deletebtn float-end p-0"
                                                    onclick="removeUnit({{ $unit->id }})" type="button"
                                                    data-bs-toggle="modal" data-bs-target="#modalRemove">
                                                    <i class="fe-trash text-danger fs-5" title="Remove" tabindex="0"
                                                        data-plugin="tippy" data-tippy-placement="top"></i>
                                                </button>
                                            </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- end col -->
        <script src="{{ subdirMix('js/units.js') }}"></script>

        <style>
            .fe-refresh-ccw {
                transform: rotate(0deg);
            }

            .fe-refresh-ccw.rotate {
                transform: rotate(-2160deg);
                transition: transform 2s linear;
            }
        </style>

    </div>
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> &copy; <span>Power Line Monitoring</span>
                </div>
                <div class="col-md-6">
                    <div class="text-md-end footer-links d-none d-sm-block">
                        <a href="{{ route('about') }}">PLMS-CLZ</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <style>
        @media pdf {
            tr>th:last-of-type {
                display: none;
            }

            tr>td:last-of-type {
                display: none;
            }
        }

        @media print {
            tr>th:last-of-type {
                display: none;
            }

            tr>td:last-of-type {
                display: none;
            }
        }
    </style>

    @include('modals.units')
@endsection

@section('script')
    <script src="{{ subdirMix('js/vendor.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ subdirMix('js/pages/datatables.init.js') }}"></script>
    <script>
        $(document).ready(function() {
            Echo.private("Units").listen("UnitUpdate", (unit) => {
                const date = new Date(unit.updated_at);

                let comma = 0;
                const updated_at = date.toLocaleString('en-US', {
                    weekday: 'short', // long, short, narrow
                    day: 'numeric', // numeric, 2-digit
                    year: 'numeric', // numeric, 2-digit
                    month: 'short', // numeric, 2-digit, long, short, narrow
                    hour: 'numeric', // numeric, 2-digit
                    minute: 'numeric', // numeric, 2-digit
                }).split('').map((x) => {
                    if (x == ',') {
                        comma++;
                        if (comma == 3) return '';
                    }

                    return x;
                }).join('');

                const tableStatus = document.getElementById(`${unit.id}.status`);
                const tableAddress = document.getElementById(`${unit.id}.address`);
                const tableUpdatedAt = document.getElementById(`${unit.id}.updated_at`);

                if (!tableStatus || !tableAddress || !tableUpdatedAt) return;

                tableStatus.textContent = `${unit.status}`;
                tableAddress.textContent = `${unit.formatted_address}`;
                tableUpdatedAt.textContent = `${updated_at}`;
            });
        });
    </script>
@endsection
