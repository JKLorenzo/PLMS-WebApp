@extends('layouts.app')

@section('head')
    <!-- third party css -->
    <link href="{{ asset('libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ subdirMix('css/config/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
@endsection

@section('content')
    <div class="container-fluid mt-2">
        <div class="col-12">
            <div class="card" style="margin-bottom: 6%">
                <div class="card-body">
                    <button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-end fs-5"
                        data-bs-toggle="modal" data-bs-target="#modalRegisterForm" data-toggle="tooltip">
                        <i class="fe-user-plus pe-1"></i> Register
                    </button>
                    <h4 class="header-title mb-4">Linemen Accounts</h4>

                    <div class="table-responsive">
                        <table class="table table-hover dt-responsive nowrap w-100" id="datatable-buttons">
                            {{-- or tickets-table --}}
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>E-mail</th>
                                    <th>Designation</th>
                                    <th>Contact no.</th>
                                    <th>Registration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($linemen as $lineman)
                                    <tr>
                                        <td class="text-capitalize">{{ $lineman->name }}</td>
                                        <td>{{ $lineman->email }}</td>
                                        <td class="text-capitalize">{{ $lineman->barangay }}</td>
                                        <td class="text-capitalize">{{ $lineman->contact_no }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lineman->created_at)->toDayDateTimeString() }}</td>

                                        <td>
                                            <div class="btn-group dropdown">
                                                <a href="" class="btn btn-light btn-sm" data-bs-toggle="dropdown"
                                                    aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" onclick="editAccount({{ $lineman->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#modalEdit" href=""><i
                                                            class="mdi mdi-pencil me-2 text-muted font-18 vertical-middle"></i>Edit
                                                        Account</a>
                                                    <a id="resetbtn" class="dropdown-item"
                                                        onclick="resetPassword({{ $lineman->id }})" data-bs-toggle="modal"
                                                        data-bs-target="#modalReset" href=""><i
                                                            class="mdi mdi-lock-reset me-2 text-muted font-18 vertical-middle"></i>Reset
                                                        Password</a>
                                                    <a id="delbtn" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#modalDelete"
                                                        onclick="deleteAccount({{ $lineman->id }})" href=""><i
                                                            class="mdi mdi-delete me-2 text-muted font-18 vertical-middle"></i>Remove</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
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

    {{-- <script>
        $(document).ready(function() {
            $('#success-alert-modal').modal('show');
        })
    </script> --}}
    @if ($errors->has('email') || $errors->has('contact_no'))
        <script>
            $(document).ready(function() {
                $('#modalRegisterForm').modal('show');
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {


        });

        function deleteAccount(id) {
            $('#delete-id').attr('action', `lineman/${id}`);
        }

        function resetPassword(id) {
            $.ajax({
                type: 'get',
                url: `lineman/${id}`,
                dataType: 'json',
                success: function(data) {
                    $('input#resetEmail').val(data.email);
                    $("#reset-id").submit(function(event) {
                        event.preventDefault();
                        $.ajax({
                            type: 'post',
                            url: `lineman/${data.id}/reset`,
                            data: $(this).serialize(),
                            dataType: 'json',
                            success: function(data) {
                                if (data === "0") {
                                    $('#modal-content').css({
                                        'border-color': 'red',
                                        'border-width': '2px',
                                    });

                                    $('#text-msg').css({
                                        'color': 'red'
                                    });

                                    setTimeout(function() {
                                        $('#modal-content').css({
                                            'border-color': '',
                                            'border-width': 'thin'
                                        });

                                        $('#text-msg').css({
                                            'color': '#262626'
                                        });
                                    }, 3000)
                                } else {
                                    $('#modalReset').fadeOut(500);

                                    setTimeout(function() {
                                        location.reload();
                                    }, 0);
                                }
                            },
                            error: (err) => console.error(err)
                        })
                    });
                },
                error: (err) => console.error(err)
            });
        }

        function editAccount(id) {
            $.ajax({
                type: 'get',
                url: `lineman/${id}`,
                dataType: 'json',
                success: function(data) {
                    $('input#updatename').val(data.name);
                    $('input#updatebarangay').val(data.barangay);
                    $('input#updateemail').val(data.email);
                    $('input#updatecontact_no').val(data.contact_no);
                    $('#modalEditForm').attr('action', `lineman/${data.id}`);
                },
                error: (err) => console.error(err)
            });
        }
    </script>

    @include('modals.lineman')
@endsection
