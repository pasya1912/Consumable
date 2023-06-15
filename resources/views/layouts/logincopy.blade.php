@extends('layouts.master.auth-master')

@section('content')
    <!-- Content -->
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
                <div class="w-100 d-flex justify-content-center">
                    <img src={{ asset("img/illustrations/girl-with-laptop-light.png") }} class="img-fluid" alt="Login image" width="700" data-app-dark-img="illustrations/boy-with-rocket-dark.png" data-app-light-img="illustrations/boy-with-rocket-light.html">
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4" style="background-color: white; max-height: 100%;">

                <div class="w-px-400 mx-auto">

                    {{-- alert when registered --}}
                    @if (session()->has('registered'))
                        <div class="alert alert-success alert-dismissible mb-5" role="alert">
                            {{ session('registered') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    @endif
                    {{-- end of alert --}}

                    {{-- alert when login error --}}
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible mb-5" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    @endif
                    {{-- end of alert --}}
                    <h2><img src="{{ asset('img/consumablelogo.png') }}" alt="Logo" style="width: 65px; height: 65px;">
                        <span class="demo text-body fw-bolder mt-2">Consumable</span></h2>

                    <form id="formAuthentication" class="mb-3" action="{{ route('login.auth') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <select class="form-select @error('username') is-invalid @enderror" id="username" name="username" autofocus autocomplete="off" required>
                                <option value="" disabled selected>Pilih Username</option>
                                @foreach($users as $user)
                                    <option value="{{ $user }}">{{ $user }}</option>
                                @endforeach
                              </select>

                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>
                        <div class="mb-3 form-password-toggle mb-5">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" autocomplete="off" required/>
                                <span class="input-group-text cursor-pointer" onclick="togglePassword()"><i id="showPassword" class="bx bx-show"></i></span>
                            </div>

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>
                        <button class="btn btn-warning d-grid w-100 py-2">
                            Sign in
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
<script>
        var x = document.getElementById("password");
        var y = document.getElementById("showPassword");

        function togglePassword() {

            if (x.type === "password") {
                x.type = "text";
                y.className = "bx bx-hide";
            } else {
                x.type = "password";
                y.className = "bx bx-show";
            }
        }

</script>
@endsection
