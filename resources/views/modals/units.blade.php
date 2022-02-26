<div class="modal fade pt-5" id="store-unit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Register unit</h5>
                <button id="close" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ URL::to('units') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="phone_number"><i class="fs-2 bi bi-sim px-1"></i>Mobile No.</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number"
                            value="{{ old('phone_number') }}" placeholder="+639" required="" />
                        @if ($errors->has('string'))
                            <div class="error text-danger">{{ $errors->first('string') }}</div>
                        @endif
                        @if ($errors->has('phone_number'))
                            <div class="error text-danger">{{ $errors->first('phone_number') }}</div>
                        @endif
                    </div>
                    <div class="modal-footer d-block">
                        <span id="processing" class="text-center d-flex justify-content-center text-dark fs-5"></span>
                        <div id="bar" class="progress h-100">
                            <div id="progress"
                                class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                                role="progressbar" style="width: 0%" aria-valuenow="40" aria-valuemin="0"
                                aria-valuemax="100">
                                <span id="percent"></span>
                            </div>
                        </div>
                        <span class="d-flex justify-content-center"></span>

                        <span id="spinner" class="float-end"></span>
                        <button id="submitbtn" type="submit" class="btn btn-warning float-end">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalRemove" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content w-100">
            <div class="modal-header">
                <h5 class="modal-title text-muted" id="exampleModalLabel">Remove Confirmation</h5>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body fs-6 text-danger">
                Are you sure you want to remove this unit?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <form id="remove-id" action="" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                        Yes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
