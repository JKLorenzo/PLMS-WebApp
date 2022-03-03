@extends('layouts.app')

@section('title', '- Units')

@section('head')
    <link href="{{ mix('css/units.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
@endsection

@section('content')
    <div class="container mt-4">
        <div class="card bg-light inner-menu shadow border-0 p-2 align-items-start flex-row">
            <form id="search-form" method="POST" class="w-100">
                @csrf
                <div class="has-search input-group">
                    <span class="fa fa-search form-control-feedback"></span>
                    <div class="w-50"><input type="text" class="form-control" placeholder="Search" name="search"
                            id="search"></div>
                    <div class="btn-group">
                        <select class="form-select dropdown-toggle" type="button" name="select">
                            <option value="">Select</option>
                            <option value="id">Id</option>
                            <option value="status">Status</option>
                            <option value="phone_number">Phone&nbsp;number</option>
                            <option value="updated_at">Updated&nbsp;Last</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-primary invisible">search</button>
                </div>
            </form>
            <button
                class="addicon text-dark bs-tooltip-top tooltip-arrow btn register d-flex align-items-center px-3 py-1 addicon"
                data-bs-toggle="modal" data-bs-target="#store-unit" data-toggle="tooltip" title="Register">
                <i class="fa-solid fa-plus pe-2"></i><span class="fs-6 text-dark opacity-100">Register</span>
            </button>
        </div>
    </div>

    <div class="container mt-3">
        <div class="row">
            <div class="table-responsive-md">
                @if (count($units) <= 0)
                    <table id="table" class="table border table-md text-start">
                        <thead class="">
                            <tr class="client--nav-tabs text-secondary">
                                <th width="5%">Id</th>
                                <th width="10%">Status</th>
                                <th width="20%">Mobile #</th>
                                <th width="20%">Longitude</th>
                                <th width="20%">Latitude</th>
                                <th width="20%">Updated&nbsp;Last</th>
                                <th width="5%">&nbsp;</th>
                            </tr>
                        </thead>
                    </table>
                @else
                    <table id="table" class="table border table-md text-start">
                        <thead class="">
                            <tr class="client--nav-tabs text-secondary">
                                <th width="5%">Id</th>
                                <th width="10%">Status</th>
                                <th width="20%">Mobile #</th>
                                <th width="20%">Longitude</th>
                                <th width="20%">Latitude</th>
                                <th width="20%">Updated&nbsp;Last</th>
                                <th width="5%">&nbsp;</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="client--nav-tabs text-secondary">
                                <th width="5%">Id</th>
                                <th width="10%">Status</th>
                                <th width="20%">Mobile #</th>
                                <th width="20%">Longitude</th>
                                <th width="20%">Latitude</th>
                                <th width="20%">Updated&nbsp;Last</th>
                                <th width="5%">&nbsp;</th>
                            </tr>
                        </tfoot>
                        <tbody class="searchbody bg-light border-0 " id="tb">
                            @foreach ($units as $unit)
                                <tr id="{{ $unit->id }}" class="trbody bg-light client--nav tdhover data">
                                    <td class="text-danger">
                                        {{ $unit->id }}
                                    </td>
                                    <td class="">
                                        {{ Str::ucfirst($unit->status) }}
                                    </td>
                                    <td class="">
                                        {{ $unit->phone_number }}
                                    </td>

                                    <td class="">
                                        {{ $unit->longitude }}
                                    </td>

                                    <td class="">
                                        {{ $unit->latitude }}
                                    </td>

                                    <td class="">
                                        {{ $unit->updated_at }}
                                    </td>
                                    <td class="">
                                        <button id="delbtn" class="btn border-0 deletebtn float-end"
                                            onclick="removeUnit({{ $unit->id }})" type="button">
                                            <i class="fas fa-trash fs-5 text-danger bs-tooltip-top tooltip-arrow"
                                                data-toggle="tooltip" data-bs-placement="top" title="Remove"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center fs-10">
                        {{ $units->links() }}
                    </div>
                @endif
            </div>
        </div>

        @if (count($errors) > 0)
            <script>
                $(document).ready(function() {
                    $('#store-unit').modal('show');
                });
            </script>
        @endif

        @include('modals.units')

        <script>
            $(document).ready(function() {
                Echo.channel("Controller").listen("ControllerUpdate", (data) => {
                    switch (data.message) {
                        case "controller 1":
                            spinner.html(
                                '<i class="fa-solid fa-check text-darm fs-4"></i>');
                            break;
                        case "controller 0":
                            submitbtn.show();
                            spinner.html(
                                '<i class="fa-solid fa-xmark text-danger fs-4"></i>');
                            submitbtn.html('re-submit');
                            break;
                        case "message 0":
                            console.log('here');
                            break;
                        default:
                            console.error(data.message);
                    }
                });

                $('#table').DataTable({
                    "lengthMenu": [
                        [10, 20, 50, -1],
                        [10, 20, 50, "All"]
                    ],
                    "bFilter": false,
                    'columnDefs': [{
                        'targets': [6],
                        'orderable': false, // orderable false
                    }],
                });

                $('[data-toggle="tooltip"]').tooltip();
                $('#search-form').submit(function(event) {
                    event.preventDefault();
                    var $form = $(this);
                    var value = $form.find("input[name='search']").val();
                    var selectValue = $form.find("select[name='select']").val();

                    $.ajax({
                        type: 'get',
                        url: 'units-search',
                        data: {
                            'searchTerm': value,
                            'selectTerm': selectValue
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('.searchbody').html(data.result);
                        },
                        error: (error) => console.log(error)
                    });
                });

                var submitbtn = $('#submitbtn');
                var phone_num = $('#phone_number');
                var closebtn = $('#close');
                var bar = $('#bar');
                var processing = $('#processing');
                var steps_bar = $('#steps-id');
                var spinner = $('#spinner');
                var form = $('#unit-form');

                steps_bar.hide();

                form.on('submit', function(e) {
                    e.preventDefault();
                    steps_bar.show('slow');
                    submitbtn.hide();

                    spinner.html(`<div class="spinner-border text-secondary" role="status">
                <span class="sr-only">Loading...</span>
                </div>`);

                    $.ajax({
                        type: "post",
                        url: "units",
                        data: $(this).serialize(),
                        success: function(data) {
                            console.log(data);
                        }
                    })
                });
            });

            function removeUnit(id) {
                $('#modalRemove').modal('show');

                $('#remove-id').submit(function(event) {
                    event.preventDefault();
                    $.ajax({
                        type: 'delete',
                        url: `units/${id}`,
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(data) {
                            $(`#${id}`).fadeOut('slow');
                            location.reload();
                        },
                        error: (error) => console.error(error)
                    });
                })
            }
        </script>
    @endsection
