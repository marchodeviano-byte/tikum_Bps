@extends('layouts.app')

@section('content')
    {{-- HEADER MOBILE --}}
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>


    {{-- PAGE HEADING --}}
    <div class="page-heading">
        <h3>Dashboard</h3>

        <p class="text-subtitle text-muted">
            Sistem Informasi Kinerja Pegawai BPS Kota Bogor
        </p>
    </div>


    <div class="page-content">

        {{-- WELCOME --}}
        <section class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        <div class="row align-items-center">

                            <div class="col-md-8">

                                <h4 class="font-bold mb-2">
                                    Selamat Datang di Dashboard
                                </h4>

                                <p class="text-muted mb-0">
                                    Kelola dan pantau data kinerja pegawai
                                    BPS Kota Bogor melalui sistem ini.
                                </p>

                            </div>

                            <div class="col-md-4 text-center mt-3 mt-md-0">

                                <i class="bi bi-bar-chart-line-fill" style="font-size: 70px;"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- STATISTIK --}}
        <section class="row">

            {{-- TOTAL PEGAWAI --}}
            <div class="col-12 col-sm-6 col-lg-3">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="stats-icon purple flex-shrink-0">
                                <i class="iconly-boldProfile"></i>
                            </div>

                            <div class="ms-3">

                                <h6 class="text-muted font-semibold mb-1">
                                    Total Pegawai
                                </h6>

                                <h4 class="font-extrabold mb-0">
                                    120
                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- HADIR --}}
            <div class="col-12 col-sm-6 col-lg-3">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="stats-icon green flex-shrink-0">
                                <i class="iconly-boldTick-Square"></i>
                            </div>

                            <div class="ms-3">

                                <h6 class="text-muted font-semibold mb-1">
                                    Hadir Hari Ini
                                </h6>

                                <h4 class="font-extrabold mb-0">
                                    108
                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- TIDAK HADIR --}}
            <div class="col-12 col-sm-6 col-lg-3">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="stats-icon red flex-shrink-0">
                                <i class="iconly-boldClose-Square"></i>
                            </div>

                            <div class="ms-3">

                                <h6 class="text-muted font-semibold mb-1">
                                    Tidak Hadir
                                </h6>

                                <h4 class="font-extrabold mb-0">
                                    12
                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- DATA SKP --}}
            <div class="col-12 col-sm-6 col-lg-3">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="stats-icon blue flex-shrink-0">
                                <i class="iconly-boldDocument"></i>
                            </div>

                            <div class="ms-3">

                                <h6 class="text-muted font-semibold mb-1">
                                    Data SKP
                                </h6>

                                <h4 class="font-extrabold mb-0">
                                    95
                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- GRAFIK --}}
        <section class="row">

            <div class="col-12 col-lg-8">

                <div class="card">

                    <div class="card-header">
                        <h4>Grafik Kehadiran Pegawai</h4>
                    </div>

                    <div class="card-body">

                        <div id="chart-kehadiran"></div>

                    </div>

                </div>

            </div>


            {{-- RINGKASAN --}}
            <div class="col-12 col-lg-4">

                <div class="card">

                    <div class="card-header">
                        <h4>Ringkasan Kehadiran</h4>
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">
                            <span>
                                <i class="bi bi-circle-fill text-success"></i>
                                Hadir
                            </span>

                            <strong>108</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>
                                <i class="bi bi-circle-fill text-warning"></i>
                                Izin
                            </span>

                            <strong>5</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>
                                <i class="bi bi-circle-fill text-danger"></i>
                                Sakit
                            </span>

                            <strong>4</strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>
                                <i class="bi bi-circle-fill text-secondary"></i>
                                Tanpa Keterangan
                            </span>

                            <strong>3</strong>
                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- AKTIVITAS TERBARU --}}
        <section class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        <h4>Aktivitas Terbaru</h4>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-hover">

                                <thead>

                                    <tr>
                                        <th>No</th>
                                        <th>NIP</th>
                                        <th>Nama Pegawai</th>
                                        <th>Aktivitas</th>
                                        <th>Status</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>
                                        <td>1</td>
                                        <td>198001012005011001</td>
                                        <td>Pegawai Contoh 1</td>
                                        <td>Menginput data kehadiran</td>
                                        <td>
                                            <span class="badge bg-success">
                                                Berhasil
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>2</td>
                                        <td>198502022010021002</td>
                                        <td>Pegawai Contoh 2</td>
                                        <td>Melakukan penilaian SKP</td>
                                        <td>
                                            <span class="badge bg-success">
                                                Berhasil
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>3</td>
                                        <td>197905152004031003</td>
                                        <td>Pegawai Contoh 3</td>
                                        <td>Import data kehadiran</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                Diproses
                                            </span>
                                        </td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>
@endsection
