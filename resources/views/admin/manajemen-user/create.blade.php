@extends('layouts.app')

@section('content')

    <div class="page-heading">

        <div class="page-title">

            <div class="row">

                <div class="col-12 col-md-8">

                    <h3>Tambah User</h3>

                    <p class="text-subtitle text-muted">
                        Tambahkan akun pegawai baru ke dalam sistem.
                    </p>

                </div>

                <div class="col-12 col-md-4 text-md-end">

                    <a href="{{ route('admin.manajemen-user.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>

                </div>

            </div>

        </div>

        @if ($errors->any())
            <div class="alert alert-danger">

                <strong>
                    Terdapat kesalahan:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif

        <section class="section">

            <div class="card">

                <div class="card-header">
                    <h4 class="card-title">
                        Informasi User
                    </h4>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('admin.manajemen-user.store') }}">

                        @csrf

                        <div class="row g-4">

                            {{-- NIP --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    NIP <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                                    value="{{ old('nip') }}" maxlength="18" inputmode="numeric"
                                    placeholder="Masukkan 18 digit NIP">

                                @error('nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <small class="text-muted">
                                    NIP harus terdiri dari 18 digit.
                                </small>

                            </div>

                            {{-- Nama --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="nama"
                                    class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}"
                                    placeholder="Nama lengkap pegawai">

                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>

                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    placeholder="contoh@email.com">

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Jabatan --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Jabatan
                                </label>

                                <select name="jabatan_id" class="form-select @error('jabatan_id') is-invalid @enderror">

                                    <option selected disabled>
                                        -- Pilih Jabatan --
                                    </option>

                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}"
                                            {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                                            {{ $jabatan->nama_jabatan }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('jabatan_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Role --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Role <span class="text-danger">*</span>
                                </label>

                                <select name="role_id" class="form-select @error('role_id') is-invalid @enderror">

                                    <option selected disabled>
                                        -- Pilih Role --
                                    </option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('role_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Status --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Status <span class="text-danger">*</span>
                                </label>

                                <select name="status" class="form-select">

                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                        Aktif
                                    </option>

                                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>

                                </select>

                            </div>

                            {{-- Password --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Password <span class="text-danger">*</span>
                                </label>

                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Minimal 8 karakter">

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Konfirmasi Password
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Ulangi password">

                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.manajemen-user.index') }}" class="btn btn-light">
                                Batal
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>
                                Simpan User
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </section>

    </div>

@endsection
