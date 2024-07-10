<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons/css/all/all.min.css">
    <title>Login</title>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            overflow: hidden;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            position: relative;
            z-index: 1;
        }

        .card {
            width: 500px;
            max-width: 400px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .card-body {
            padding: 0;
        }

        .card-header {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            color: white;
            text-align: center;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            padding: 1rem;
            margin: -2rem -2rem 2rem -2rem;
            position: relative;
            z-index: 1;
        }

        .card-header h1,
        .card-header h5 {
            margin: 0;
        }

        .btn-primary {
            background: #00224D;
            border-radius: 20px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background: #26355D;
        }

        .form-control {
            border-radius: 20px;
        }

        .form-control:focus {
            border-color: #26355D;
        }

        .input-group-text {
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
        }

        .curved-background-top {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            border-bottom-left-radius: 50% 20%;
            border-bottom-right-radius: 50% 20%;
            z-index: 0;
        }

        .curved-background-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background: linear-gradient(to right, #254562, #17c8d1);
            border-top-left-radius: 50% 20%;
            border-top-right-radius: 50% 20%;
            z-index: 0;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1060;
        }
    </style>
</head>

<body>
    <div class="curved-background-top"></div>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Welcome to SASE</h1>
                <h5>(Sistem Arsip Surat Eletronik)</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="loginForm" action="{{ route('login-username-post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fi fi-rr-users"></i></span>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Username" required autocomplete="off">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fi fi-rr-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required autocomplete="off">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Submit</button>
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('login-email') }}" class="btn btn-secondary w-100">Email</a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="btn btn-secondary w-100">Register</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="curved-background-bottom"></div>

    <div class="toast-container">
        <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-delay="5000">
            <div class="toast-header">
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Please fill out all required fields.
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            function showToast(message) {
                $('.toast-body').html(message);
                var toastEl = new bootstrap.Toast(document.getElementById('errorToast'));
                toastEl.show();
            }

            $('#loginForm').validate({
                rules: {
                    username: {
                        required: true
                    },
                    password: {
                        required: true
                    }
                },
                messages: {
                    username: {
                        required: "Username is required"
                    },
                    password: {
                        required: "Password is required"
                    }
                },
                errorPlacement: function(error, element) {
                    // Accumulate error messages
                    let errorMessages = '';
                    $('#loginForm .is-invalid').each(function() {
                        errorMessages +=
                            `<p>${$(this).attr('name') === 'username' ? 'Username is required' : 'Password is required'}</p>`;
                    });
                    showToast(errorMessages);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    // Optional: Clear toast on form submission
                    $('.toast-body').html('');
                    form.submit();
                }
            });
        });
    </script>
</body>

</html
