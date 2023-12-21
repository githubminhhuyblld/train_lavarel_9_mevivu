@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-4">
                <div class="card">
                    <div class="card-header">Login Page</div>
                    <div class="card-body">
                        <form id="loginForm" method="POST" action="{{ route('login.perform') }}" data-parsley-validate>
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required data-parsley-type="email">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        $(document).ready(function() {

            $('#loginForm').submit(function(e) {
                e.preventDefault();

                const email = $('#email').val();
                const password = $('#password').val();
                if ($(this).parsley().isValid()) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('login.perform') }}',
                        data: {
                            email: email,
                            password: password,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            showToast("Login successfully");
                            window.location.reload();
                        },
                        error: function(xhr) {
                            if (xhr.status === 401) {
                                var errorMessage = xhr.responseJSON.error;
                                alert(errorMessage);
                            } else {
                                alert("An error occurred. Please try again.");
                            }
                            console.error("Error: " + xhr.status);
                        }
                    });
                }


            });
        });


    </script>
@endsection
