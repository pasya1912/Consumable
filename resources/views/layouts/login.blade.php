@extends('layouts.master.auth-master')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">
        <div class="card-body">
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
          <h4 class="mb-2">Welcome to Consumable! 👋</h4>
          <p class="mb-4">Please sign-in to your account</p>

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
    <!-- /Register -->
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
