@extends('layouts.app')

@section('content')

    <div class="page-heading">

        <div class="page-title">

            <div class="row">

                <div class="col-md-8">

                    <h3>Edit User</h3>

                    <p class="text-subtitle text-muted">
                        Perbarui informasi akun pegawai.
                    </p>

                </div>

                <div class="col-md-4 text-md-end">

                    <a href="{{ route('admin.manajemen-user.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>

                </div>

            </div>

        </div>

        @if ($errors->any())
            <div class="alert alert-danger">

                <ul class="mb-0">

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

                    <form method="POST" action="{{ route('admin.manajemen-user.update', $manajemen_user) }}">

                        @csrf
                        @method('PUT')

                        <div class="row g-4">

                            <div class="col-md-6">

                                <label class="form-label">
                                    NIP <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="nip" maxlength="18" inputmode="numeric" class="form-control"
                                    value="{{ old('nip', $manajemen_user->nip) }}">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Nama Lengkap
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="nama" class="form-control"
                                    value="{{ old('nama', $manajemen_user->nama) }}">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Email
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $manajemen_user->email) }}">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Jabatan
                                </label>

                                <select name="jabatan_id" class="form-select">

                                    <option value="">
                                        -- Pilih Jabatan --
                                    </option>

                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}"
                                            {{ old('jabatan_id', $manajemen_user->jabatan_id) == $jabatan->id ? 'selected' : '' }}>
                                            {{ $jabatan->nama_jabatan }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Role
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="role_id" class="form-select">

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id', $manajemen_user->role_id) == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status" class="form-select">

                                    <option value="1"
                                        {{ old('status', $manajemen_user->status ? '1' : '0') == '1' ? 'selected' : '' }}>
                                        Aktif
                                    </option>

                                    <option value="0"
                                        {{ old('status', $manajemen_user->status ? '1' : '0') == '0' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Password Baru
                                </label>

                                <input type="password" name="password" class="form-control"
                                    placeholder="Kosongkan jika tidak ingin mengubah">

                                <small class="text-muted">
                                    Minimal 8 karakter jika ingin mengganti password.
                                </small>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Konfirmasi Password Baru
                                </label>

                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Ulangi password baru">

                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>
                                Simpan Perubahan
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </section>

    </div>

@endsection
