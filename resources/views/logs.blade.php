@extends('layouts.app')

@section('head')
    <link href="{{ asset('libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ subdirMix('css/config/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="{{ asset('libs/tippy.js/tippy.all.min.js') }}"></script>
    <link href="https://nightly.datatables.net/buttons/css/buttons.dataTables.css?_=c6b24f8a56e04fcee6105a02f4027462.css"
        rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="container-fluid mt-2">
        <div class="col-12">
            <div class="card" style="margin-bottom: 6%">
                <div class="card-body">
                    <h3 class="header-title mb-2"><label>Logs: Unit {{ $id }}</label></h3>
                    <div class="table-responsive">
                        <table class="table table-bordered m-0 dt-responsive nowrap w-100" id="table">
                            {{-- or tickets-table --}}
                            <thead>
                                <tr>
                                    <th width="20%">ID</th>
                                    <th width="20%">Status</th>
                                    <th width="20%">Date Created</th>

                                </tr>
                            </thead>
                            <div class="card">

                                <tbody>
                                    @foreach ($logs as $log)
                                        <tr>
                                            <td> {{ $log->id }} </td>
                                            <td class="text-capitalize"> {{ $log->status }} </td>
                                            <td> {{ \Carbon\Carbon::parse($log->created_at)->toDayDateTimeString() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- end col -->
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

    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                dom: 'Bfrtip',
                ordering: false,
                buttons: [{
                        extend: "copy",
                        className: "btn-light mb-1"
                    },
                    {
                        extend: "print",
                        className: "btn-light mb-1",
                    }
                ],
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    }
                },
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });
        });
    </script>
@endsection
