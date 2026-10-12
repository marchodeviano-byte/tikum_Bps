@extends('layouts.app')

@section('content')
    <style>
        .panitia-form-page {
            padding-bottom: 30px;
        }

        .panitia-form-title h3 {
            color: #07549b;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .panitia-form-title p {
            color: #7c8a9a;
            font-size: 13.5px;
        }

        .panitia-form-card {
            background: #ffffff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .panitia-form-card .card-header {
            background: #075a9f;
            color: #ffffff;
            padding: 18px 22px;
            border: none;
        }

        .panitia-form-card .card-header h4 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
        }

        .panitia-form-card .card-header p {
            margin: 4px 0 0;
            color: rgba(255, 255, 255, .78);
            font-size: 12.5px;
        }

        .panitia-form-card .card-body {
            padding: 26px;
        }

        .panitia-form-page .form-label {
            color: #465466;
            font-weight: 600;
            font-size: 13.5px;
        }

        .panitia-form-page .form-control {
            border-color: #d7dee7;
            color: #526170;
            min-height: 42px;
        }

        .panitia-form-page .form-control:focus {
            border-color: #07549b;
            box-shadow: 0 0 0 3px rgba(7, 84, 155, .12);
        }

        .file-lama {
            margin-top: 8px;
            padding: 10px 12px;
            background: #f5f8fb;
            border-radius: 7px;
            color: #526170;
            font-size: 13px;
        }

        .file-lama a {
            color: #07549b;
            font-weight: 600;
            text-decoration: none;
        }

        .panitia-pdf-info {
            margin-top: 7px;
            color: #8a96a3;
            font-size: 12px;
        }

        .panitia-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            margin-top: 25px;
        }

        .btn-simpan-panitia {
            background: #07549b;
            color: #ffffff;
            border: none;
            border-radius: 7px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .btn-simpan-panitia:hover {
            background: #06457e;
            color: #ffffff;
        }

        body.theme-dark .panitia-form-title h3,
        html.theme-dark .panitia-form-title h3 {
            color: #8db9ff;
        }

        body.theme-dark .panitia-form-title p,
        html.theme-dark .panitia-form-title p {
            color: #aeb5c5;
        }

        body.theme-dark .panitia-form-card,
        html.theme-dark .panitia-form-card {
            background: #1f1e2e;
        }

        body.theme-dark .panitia-form-page .form-label,
        html.theme-dark .panitia-form-page .form-label {
            color: #c3c8d5;
        }

        body.theme-dark .panitia-form-page .form-control,
        html.theme-dark .panitia-form-page .form-control {
            background: #242334;
            border-color: #3b394b;
            color: #d4d8e2;
        }

        body.theme-dark .file-lama,
        html.theme-dark .file-lama {
            background: #282737;
            color: #c3c8d5;
        }
    </style>


    <div class="panitia-form-page">

        <div class="page-heading">

            <div class="panitia-form-title">

                <h3>
                    Edit SK Panitia
                </h3>

                <p>
                    Surat Keputusan &raquo; Panitia &raquo; Edit
                </p>

            </div>

        </div>


        <div class="card panitia-form-card">

            <div class="card-header">

                <h4>
                    <i class="bi bi-pencil-square me-1"></i>
                    Edit Surat Keputusan
                </h4>

                <p>
                    Perbarui data Surat Keputusan Panitia.
                </p>

            </div>


            <div class="card-body">

                <form action="{{ route('admin.sipeter.surat-keputusan.panitia.update', $data->id) }}" method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    @method('PUT')


                    <div class="mb-3">

                        <label class="form-label">
                            Nomor SK
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text" name="no_sk" value="{{ old('no_sk', $data->no_sk) }}"
                            class="form-control @error('no_sk') is-invalid @enderror" maxlength="150">

                        @error('no_sk')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Tanggal SK
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date" name="tanggal_sk"
                            value="{{ old('tanggal_sk', $data->tanggal_sk?->format('Y-m-d')) }}"
                            class="form-control @error('tanggal_sk') is-invalid @enderror">

                        @error('tanggal_sk')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Perihal
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text" name="perihal" value="{{ old('perihal', $data->perihal) }}"
                            class="form-control @error('perihal') is-invalid @enderror" maxlength="255">

                        @error('perihal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            File Surat Keputusan
                        </label>

                        @if ($data->file_pdf)
                            <div class="file-lama">

                                <i class="bi bi-file-earmark-pdf text-danger me-1"></i>

                                File saat ini:

                                <a href="{{ route('admin.sipeter.surat-keputusan.panitia.show', $data->id) }}"
                                    target="_blank" rel="noopener noreferrer">
                                    Lihat PDF
                                </a>

                            </div>
                        @endif


                        <input type="file" name="file_pdf" accept="application/pdf,.pdf"
                            class="form-control mt-2 @error('file_pdf') is-invalid @enderror">

                        <div class="panitia-pdf-info">
                            <i class="bi bi-info-circle me-1"></i>
                            Kosongkan jika tidak ingin mengganti file PDF.
                            Maksimal 10 MB.
                        </div>

                        @error('file_pdf')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="panitia-form-actions">

                        <a href="{{ route('admin.sipeter.surat-keputusan.panitia.index') }}" class="btn btn-light">
                            Batal
                        </a>

                        <button type="submit" class="btn-simpan-panitia">
                            <i class="bi bi-check2-circle me-1"></i>
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection
