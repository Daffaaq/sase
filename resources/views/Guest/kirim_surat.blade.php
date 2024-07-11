<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons/css/all/all.min.css">
    <title>Kirim Surat</title>
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

        .steps-panel {
            position: fixed;
            top: 0;
            left: -300px;
            width: 300px;
            height: 100%;
            background: linear-gradient(to bottom, #4facfe, #00f2fe);
            box-shadow: -4px 0 8px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease;
            z-index: 1050;
            padding: 20px;
            overflow-y: auto;
            color: white;
        }

        .steps-panel.open {
            left: 0;
        }

        .steps-panel h5 {
            color: rgb(255, 38, 0)
        }

        .steps-panel ol,
        .steps-panel button {
            color: white;
        }

        .steps-panel .list-group-item {
            background: transparent;
            border: none;
            padding: 0.5rem 1rem;
            text-align: justify;
        }

        .steps-panel .list-group-item::before {
            background: #fff;
        }

        .progress {
            position: relative;
            height: 20px;
            border-radius: 20px;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(to right, #4facfe, #00f2fe);
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
                <form id="upload-form" action="{{ route('post-surat-eksternal') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fi fi-rr-user"></i></span>
                        <input type="text" class="form-control" id="nama_pengirim" name="nama_pengirim"
                            placeholder="Nama Lengkap" required autocomplete="off">
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fi fi-rr-mailbox-envelope"></i></span>
                        <input type="email" class="form-control" id="email_pengirim" name="email_pengirim"
                            placeholder="Email" required autocomplete="off">
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fi fi-rr-building"></i></span>
                        <input type="text" class="form-control" id="instansi_pengirim" name="instansi_pengirim"
                            placeholder="Nama Perusahaan" required autocomplete="off">
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fi fi-rr-smartphone"></i></span>
                        <input type="tel" class="form-control" id="no_telp_pengirim" name="no_telp_pengirim"
                            placeholder="No Telp" required autocomplete="off">
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fi fi-rr-input-numeric"></i></span>
                        <input type="text" class="form-control" id="nomer_surat_masuk" name="nomer_surat_masuk"
                            placeholder="Nomer Surat Masuk" required autocomplete="off">
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fi fi-rr-text"></i></span>
                        <textarea class="form-control" id="deskripsi_surat" name="deskripsi_surat" placeholder="Deskripsi Surat" required
                            autocomplete="off"></textarea>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fi fi-rr-file-upload"></i></span>
                        <input type="file" class="form-control" id="file" name="file" required
                            autocomplete="off">
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fi fi-rr-list"></i></span>
                        <select class="form-control" id="category_surat_id" name="category_surat_id" required>
                            <option value="" disabled selected>Pilih Kategori Surat</option>
                            @foreach ($category_surat as $category)
                                <option value="{{ $category->id }}">{{ $category->name_jenis_surat_masuk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fi fi-rr-list"></i></span>
                        <select class="form-control" id="sifat_surat_id" name="sifat_surat_id" required>
                            <option value="" disabled selected>Pilih Sifat Surat</option>
                            @foreach ($sifat_surat as $sifat)
                                <option value="{{ $sifat->id }}">{{ $sifat->name_sifat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="progress mb-3" style="height: 20px; display: none;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                            style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <button type="button" id="previewButton" class="btn btn-secondary w-100 mb-3"
                        style="display: none;" data-bs-toggle="modal" data-bs-target="#previewModal">Preview</button>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Submit</button>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" id="stepsButton"
                                class="btn btn-secondary w-100">Langkah-langkah</button>
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
        <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-delay="5000">
            <div class="toast-header">
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                File uploaded successfully.
            </div>
        </div>
    </div>

    <!-- Modal for Preview -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">File Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="filePreview" src="" style="width: 100%; height: 500px;"
                        frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel for Steps -->
    <div id="stepsPanel" class="steps-panel">
        <h5 class="text-center">Langkah-langkah Pengiriman Surat</h5>
        <hr>
        <ol class="list-group list-group-numbered">
            <li class="list-group-item">
                Isikan formulir dan upload surat berbentuk PDF.
            </li>
            <li class="list-group-item">
                Silahkan klik preview jika ingin melihat pratinjau surat.
            </li>
            <li class="list-group-item">
                Silahkan cek email secara berkala karena statusnya akan kami informasikan di email.
            </li>
        </ol>
        <button type="button" class="btn btn-secondary mt-3 w-100" id="closeStepsPanel">Close</button>
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
            function showToast(message, toastId) {
                $('.toast-body').html(message);
                var toastEl = new bootstrap.Toast(document.getElementById(toastId));
                toastEl.show();
            }

            $('#file').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const fileURL = URL.createObjectURL(file);
                    $('#filePreview').attr('src', fileURL);
                    $('#previewButton').show();
                    showProgressBar(file.size);
                } else {
                    $('#previewButton').hide();
                    $('.progress').hide();
                }
            });

            function showProgressBar(fileSize) {
                $('.progress').show();
                var progressBar = $('.progress-bar');
                var width = 0;
                var interval = setInterval(function() {
                    if (width >= 100) {
                        clearInterval(interval);
                        $('.progress').hide();
                        progressBar.width(0);
                        progressBar.attr('aria-valuenow', 0);
                        showToast('File uploaded successfully.', 'successToast');
                    } else {
                        width++;
                        progressBar.width(width + '%');
                        progressBar.attr('aria-valuenow', width);
                    }
                }, calculateInterval(fileSize));
            }

            function calculateInterval(fileSize) {
                const maxFileSize = 10 * 1024 * 1024; // 10MB in bytes
                const baseInterval = 50; // base interval in milliseconds for 1% increment
                const fileSizeRatio = fileSize / maxFileSize;
                const interval = baseInterval / fileSizeRatio;
                return Math.max(interval, 5); // ensuring the interval is not too fast
            }

            $('#stepsButton').on('click', function() {
                $('#stepsPanel').addClass('open');
            });

            $('#closeStepsPanel').on('click', function() {
                $('#stepsPanel').removeClass('open');
            });

            $('#upload-form').validate({
                rules: {
                    nama_pengirim: {
                        required: true
                    },
                    email_pengirim: {
                        required: true,
                        email: true
                    },
                    instansi_pengirim: {
                        required: true
                    },
                    no_telp_pengirim: {
                        required: true,
                    },
                    nomer_surat_masuk: {
                        required: true
                    },
                    file: {
                        required: true
                    },
                    category_surat_id: {
                        required: true
                    },
                    sifat_surat_id: {
                        required: true
                    }
                },
                messages: {
                    nama_pengirim: {
                        required: "Nama pengirim is required"
                    },
                    email_pengirim: {
                        required: "Email pengirim is required",
                        email: "Please enter a valid email address"
                    },
                    instansi_pengirim: {
                        required: "Instansi pengirim is required"
                    },
                    no_telp_pengirim: {
                        required: "Nomor telepon pengirim is required",
                    },
                    nomer_surat_masuk: {
                        required: "Nomor surat is required"
                    },
                    file: {
                        required: "File is required"
                    },
                    category_surat_id: {
                        required: "Kategori surat is required"
                    },
                    sifat_surat_id: {
                        required: "Sifat surat is required"
                    }
                },
                errorPlacement: function(error, element) {
                    var errorMessage = '';
                    $('#upload-form .is-invalid').each(function() {
                        const name = $(this).attr('id');
                        errorMessage += `<p>${name} is required</p>`;
                    });
                    showToast(errorMessage, 'errorToast');
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    $('.toast-body').html('');
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>
