@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5 py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header header-title justify-content-start bg-light">
                        <i class="fe-lock fs-3 px-1"></i> <span>Reset Password</span>
                    </div>

                    <div class="card-body d-flex justify-content-center">
                        <form class="form w-75" role="form" method="POST" action="{{ route('user.update') }}">
                            @csrf
                            <div class="form-group p-1">
                                <label for="inputPasswordOld">Current Password</label>
                                <input type="password" class="form-control @error('pass') is-invalid @enderror"
                                    id="inputPasswordOld" name="password" value="{{ old('password') }}" required=""
                                    autocomplete>

                                @if ($errors->has('pass'))
                                    <div class="error text-danger">{{ $errors->first('pass') }}</div>
                                @endif
                            </div>

                            <div class="form-group p-1">
                                <label for="inputPasswordNew">New Password</label>
                                <input type="password" class="form-control @error('pass') is-invalid @enderror"
                                    id="inputPasswordNew" name="new-password" value="{{ old('new-password') }}"
                                    required="" autocomplete>

                                @if ($errors->has('new-password'))
                                    <div class="error text-danger">{{ $errors->first('new-password') }}</div>
                                @endif

                                @if ($errors->has('newpass'))
                                    <div class="error text-danger">{{ $errors->first('newpass') }}</div>
                                @endif

                                @if (!$errors->has('new-password') && !$errors->has('newpass'))
                                    <span class="text-muted" id="new-password">
                                        The New password must be at least 8-10 characters.
                                    </span>
                                @endif
                            </div>

                            <div class="form-group p-1">
                                <label for="inputPasswordNewVerify">Confirm Password</label>
                                <input type="password" class="form-control @error('verify') is-invalid @enderror"
                                    id="inputPasswordNewVerify" name="verify" value="{{ old('verify') }}" required=""
                                    autocomplete>
                                @if ($errors->has('verify'))
                                    <div class="error text-danger">
                                        {{ __('Password do not match') }}</div>
                                @endif
                                @if (!$errors->has('verify'))
                                    <span class="text-muted">
                                        To confirm, type the new password again.
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                {{-- <a class="btn btn-success btn-lg float-end py-1 px-3 fs-5 mt-2" data-bs-toggle="modal"
                                    data-bs-target="#modalConfirm" type="submit">

                                    Save
                                </a> --}}
                                <button type="submit" class="btn btn-primary mt-1 float-end"><i
                                        class="mdi mdi-content-save-move pe-1"></i>Save changes</button>

                            </div>
                            @include('modals.password')
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
@section('script')
    <script src="{{ subdirMix('js/vendor.min.js') }}"></script>
@endsection
@endsection
