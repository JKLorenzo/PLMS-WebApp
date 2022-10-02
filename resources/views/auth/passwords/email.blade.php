@extends('layouts.app')

@section('head')
@endsection

@section('content')
    <div class="container col-md-8" style="margin-top: 10%">
        <div class="container">
            @if (session('status'))
                <div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content modal-filled bg-success">
                            <div class="modal-body p-4">
                                <div class="text-center">
                                    <i class="mdi mdi-email-send fs-3"></i>
                                    <h5 class="mt-2 text-white">Well Done! <i class="ti-thumbs-up"></i></h5>
                                    <p class="mt-3 text-white">{{ session('status') }}</p>
                                    <a type="button" href="https://mail.google.com/mail/u/0/" class="btn btn-light my-2"><i
                                            class="fab fa-google-plus-g fs-4"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-6 container-fluid mb-3" role="document">
            <div id="modal-content" class="modal-content w-100 shadow">
                <div class="modal-header border-0">
                    <span class="header-title">Reset Password</span>
                </div>
                <div class="modal-body border-0">
                    <div class="panel-body">
                        <div class="text-center">
                            <h3><i class="fa fa-lock fa-3x text-danger"></i></h3>
                            <h4 class="text-center text-dark">Forgot password?</h4>
                            <div class="panel-body">
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="form-group pt-3 justify-content-center">
                                        <div class="input-group">
                                            <i
                                                class="bi bi-envelope-check fs-2 pe-sm-2 text-dark opacity-75 align-items-center"></i>&nbsp;
                                            <input id="email" type="email"
                                                class="form-control float-start @error('email') is-invalid @enderror"
                                                name="email" required autocomplete="email" autofocus
                                                placeholder="Enter E-mail Address here.." value="{{ old('email') }}">
                                            @error('email')
                                                <div class="invalid-feedback" role="alert">
                                                    <span class="text-danger fs-5">{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>
                                        <span id="errormsg"></span>
                                    </div>
                                    <div class="form-group pt-2">
                                        <button type="submit" class="btn btn-secondary">
                                            {{ __('Send Password Reset Link') }}
                                        </button>
                                    </div>
                                    <input type="hidden" class="hide" name="token" id="token" value="">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="container d-flex justify-content-center p-1 fs-5">
                <a href="{{ route('login') }}" class="waves-effect waves-light btn btn-info">
                    <span>
                        <i class="mdi mdi-keyboard-return"></i> <span>Back to Login </span>
                    </span>
                </a>
            </div>
            <div class="d-flex justify-content-center opacity-75 mt-3">
                <span>Copyright &copy; 2022 &mdash; Power Line Monitoring</span>
            </div>
        </div>
    @section('script')
        <script>
            $(document).ready(function() {
                $('#success-alert-modal').modal('show');
            })
        </script>
    @endsection
@endsection
