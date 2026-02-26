<!DOCTYPE html>
<html>
<head>
    <title>Create New Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f5f5f5;">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header text-center fw-bold">
                    Create New Password
                </div>

                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <input type="email"
                               name="email"
                               class="form-control mb-3"
                               value="{{ old('email', request()->email) }}"
                               placeholder="Email"
                               required>

                        <input type="password"
                               name="password"
                               class="form-control mb-3"
                               placeholder="New Password"
                               required>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control mb-3"
                               placeholder="Confirm Password"
                               required>

                        <button type="submit" class="btn btn-success w-100">
                            Reset Password
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
