<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons/css/all/all.min.css">
    <title>Page Error 404</title>
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
            flex-direction: column;
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
            text-align: center;
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

        .error-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        .error-message {
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="curved-background-top"></div>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>404</h1>
                <h5>Page Not Found</h5>
            </div>
            <div class="card-body">
                <img id="error-image" src="" alt="404 Not Found" class="error-image">
                <p class="error-message">The page you are looking for does not exist.</p>
                <a href="/" class="btn btn-primary">Go to Homepage</a>
            </div>
        </div>
    </div>
    <div class="curved-background-bottom"></div>

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
            const images = [
                "{{ asset('SbAdmin/img/404-1.png') }}",
                "{{ asset('SbAdmin/img/404-2.png') }}",
                "{{ asset('SbAdmin/img/404-3.png') }}",
                "{{ asset('SbAdmin/img/404-4.png') }}"
            ];
            const randomImage = images[Math.floor(Math.random() * images.length)];
            $('#error-image').attr('src', randomImage);
        });
    </script>
</body>

</html>
