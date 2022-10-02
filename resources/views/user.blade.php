@extends('layouts.app')


@section('head')
@endsection


@section('content')
    <div class="row">
        <div class="col-lg-4 container mt-5">
            <div class="card text-center">
                <div class="card-body bg-light">
                    <img src="{{ subdirMix('images/user-3.jpg') }}" class="rounded-circle avatar-lg img-thumbnail"
                        alt="profile-image">

                    <h4 class="mb-0">{{ Auth::user()->name }}</h4>
                    {{-- <p class="text-muted">{{Auth::user()->email}}</p> --}}

                    {{-- <button type="button" class="btn btn-success btn-xs waves-effect mb-2 waves-light">Follow</button>
                <button type="button" class="btn btn-danger btn-xs waves-effect mb-2 waves-light">Message</button> --}}

                    <div class="text-start mt-3">
                        <h4 class="font-13 text-uppercase">About Me :</h4>
                        <p class="text-muted font-13 mb-3">
                            Hi I'm {{ Auth::user()->name }}, A admin User of Power Line Monitoring.
                        </p>
                        <p class="text-muted mb-2 font-13"><strong>Name :</strong> <span
                                class="ms-2">{{ Auth::user()->name }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span
                                class="ms-2">{{ Auth::user()->email }}</span></p>

                    </div>

                    {{-- <ul class="social-list list-inline mt-3 mb-0">
                    <li class="list-inline-item">
                        <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i
                                class="mdi mdi-facebook"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i
                                class="mdi mdi-google"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="javascript: void(0);" class="social-list-item border-info text-info"><i
                                class="mdi mdi-twitter"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i
                                class="mdi mdi-github"></i></a>
                    </li>
                </ul> --}}
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
