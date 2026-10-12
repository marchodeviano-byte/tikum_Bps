@extends('layouts.app')

@section('title', 'Import Kehadiran')

@section('content')

    <div class="page-heading">

        <div class="page-title mb-4">
            <div class="row align-items-center">

                <div class="col-12 col-md-7">
                    <h3 class="mb-1">
                        Import Kehadiran
                    </h3>

                    <p class="text-subtitle text-muted mb-0">
                        Import data kehadiran pegawai melalui file Excel.
                    </p>
                </div>

                <div class="col-12 col-md-5 mt-3 mt-md-0">
                    <div class="d-flex justify-content-md-end">
                        <a href="{{ route('admin.kehadiran.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>

            </div>
        </div>


        <section class="section">

            <div class="row justify-content-center">

                <div class="col-lg-8">

                    <div class="card">

                        <div class="card-header">
                            <h5 class="mb-1">
                                Upload File Excel
                            </h5>

                            <p class="text-muted mb-0">
                                Gunakan file Presensi Bulanan sesuai template BPS.
                            </p>
                        </div>


                        <div class="card-body">

                            {{-- ERROR VALIDATION --}}
                            @if ($errors->any())

                                <div class="alert alert-danger">
                                    <div class="d-flex align-items-start">

                                        <i class="bi bi-exclamation-circle me-2"></i>

                                        <div>
                                            <strong>
                                                File tidak dapat diproses.
                                            </strong>

                                            <ul class="mb-0 mt-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>

                                    </div>
                                </div>

                            @endif


                            {{-- ERROR IMPORT --}}
                            @if (session('error_import'))
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    {{ session('error_import') }}
                                </div>
                            @endif


                            {{-- SUCCESS --}}
                            @if (session('success_import'))
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    {{ session('success_import') }}
                                </div>
                            @endif


                            {{-- FORM UPLOAD --}}
                            <form method="POST" action="{{ route('admin.kehadiran.import.preview') }}"
                                enctype="multipart/form-data" id="formUploadExcel">

                                @csrf

                                <div class="upload-box">

                                    <div class="upload-icon">
                                        <i class="bi bi-file-earmark-excel"></i>
                                    </div>

                                    <h5>
                                        Pilih File Excel
                                    </h5>

                                    <p class="text-muted mb-2">
                                        Format yang diperbolehkan:
                                        <strong>.xlsx</strong> atau
                                        <strong>.xls</strong>
                                    </p>


                                    <input type="file" name="file" id="file" class="form-control"
                                        accept=".xlsx,.xls" required>


                                    <small class="text-muted d-block mt-2">
                                        Maksimal ukuran file 10 MB.
                                    </small>

                                </div>


                                <div class="alert alert-warning mt-4">

                                    <div class="d-flex">

                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>

                                        <div>

                                            <strong>Perhatian</strong>

                                            <div class="mt-1">

                                                Jangan mengubah struktur utama
                                                template Presensi Bulanan.

                                                Sistem akan membaca Tahun,
                                                Bulan, NIP, Nama, tanggal,
                                                serta data presensi secara otomatis.

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <div class="d-flex justify-content-end mt-4">

                                    <button type="submit" class="btn btn-primary" id="btnPreview">

                                        <i class="bi bi-eye me-1"></i>

                                        Preview Data

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>


    <style>
        .upload-box {
            padding: 35px;
            text-align: center;
            border: 2px dashed #dfe3e8;
            border-radius: 12px;
            background: #fafbfc;
        }

        .upload-icon {
            width: 65px;
            height: 65px;
            margin: 0 auto 15px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background: #eef4ff;
            color: #435ebe;

            font-size: 30px;
        }

        .upload-box input[type="file"] {
            max-width: 500px;
            margin: 20px auto 0;
            text-align: left;
        }

        #btnPreview {
            min-width: 145px;
            font-weight: 600;
        }

        body.theme-dark .upload-box,
        html.theme-dark .upload-box {
            background: #202638;
            border-color: #3a4257;
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('formUploadExcel');
            const button = document.getElementById('btnPreview');
            const fileInput = document.getElementById('file');

            if (!form || !button) {
                return;
            }

            form.addEventListener('submit', function(event) {

                /*
                 * Pastikan file dipilih.
                 */
                if (!fileInput.files.length) {

                    event.preventDefault();

                    fileInput.focus();

                    return;
                }


                /*
                 * Jangan submit dua kali.
                 */
                button.disabled = true;

                button.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"
                  role="status"
                  aria-hidden="true"></span>

            Memproses...
        `;

            });

        });
    </script>

@endsection
