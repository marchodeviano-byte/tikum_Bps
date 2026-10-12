<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\PelaporanController;
use App\Http\Controllers\Admin\SkpController;
use App\Http\Controllers\Admin\PenilaianController;
use App\Http\Controllers\Admin\MonitoringPelaporanController;
use App\Http\Controllers\Admin\MonitoringSkpController;
use App\Http\Controllers\Admin\MitraPenilaianController;
use App\Http\Controllers\Admin\MitraBlacklistController;
use App\Http\Controllers\Admin\SuratkeputusanpanitiaController;
use App\Http\Controllers\Admin\SuratkeputusanhasilController;
use App\Http\Controllers\Admin\PenilaianTahap1Controller;
use App\Http\Controllers\Admin\SipeterPertanyaanController;
use App\Http\Controllers\Admin\PenilaianTahap2PanitiaController;
use App\Http\Controllers\Admin\AngkaKreditController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "web" middleware group.
|
*/

Route::get('/', function () {
    return view('admin.home');
})->name('dashboard');


// =========================================================
// MANAJEMEN USER
// =========================================================
// CRUD standar -> Route::resource memang paling pas di sini.
Route::resource(
    'manajemen-user',
    UserController::class
)->names('admin.manajemen-user');


// =========================================================
// KEHADIRAN
// =========================================================
Route::prefix('kinerjapegawai-kehadiran')
    ->name('admin.kehadiran.')
    ->controller(KehadiranController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('/kjk/export', 'exportKjk')->name('kjk.export');
        Route::get('/import', 'importForm')->name('import');
        Route::post('/import/preview', 'importPreview')->name('import.preview');
        Route::post('/import/store', 'importStore')->name('import.store');
    });


// =========================================================
// PELAPORAN (Upload)
// =========================================================
Route::prefix('kinerjapegawai-pelaporan')
    ->name('admin.pelaporan.')
    ->controller(PelaporanController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
    });

Route::name('pelaporan.')
    ->controller(PelaporanController::class)
    ->group(function () {

        Route::post('/pelaporan/upload', 'upload')->name('upload');
        Route::delete('/pelaporan/{pelaporan}', 'destroy')->name('destroy');
    });


// =========================================================
// SKP (Upload)
// =========================================================
Route::prefix('kinerjapegawai-skp')
    ->name('admin.skp.')
    ->controller(SkpController::class)
    ->group(function () {

        // Penetapan
        Route::get('/penetapan', 'index')
            ->name('penetapan');

        // Evaluasi
        Route::get('/evaluasi', 'evaluasi')
            ->name('evaluasi');

        // Upload SKP Penetapan
        Route::post('/upload', 'upload')
            ->name('upload');

        // Hapus SKP Penetapan
        Route::delete('/{skp}', 'destroy')
            ->name('destroy');

        // Upload Evaluasi
        Route::post('/evaluasi/upload', 'uploadEvaluasi')
            ->name('evaluasi.upload');

        // Persetujuan Evaluasi
        Route::post(
            '/evaluasi/{skpEvaluasi}/setujui',
            'setujuiEvaluasi'
        )->name('evaluasi.setujui');

        // Penolakan Evaluasi
        Route::post(
            '/evaluasi/{skpEvaluasi}/tolak',
            'tolakEvaluasi'
        )->name('evaluasi.tolak');

        Route::post(
            '/evaluasi/{skpEvaluasi}/dokumen-review',
            'reviewDokumen'
        )->name('evaluasi.dokumen.review');
    });


// =========================================================
// ANGKA KREDIT (Kinerja Pegawai > Skp > Angka Kredit)
// =========================================================
Route::prefix('kinerjapegawai-skp/angka-kredit')
    ->name('admin.skp.angka-kredit.')
    ->controller(AngkaKreditController::class)
    ->group(function () {

        // Halaman utama (filter tahun)
        Route::get('/', 'index')->name('index');

        // Simpan angka kredit awal, semester 1, semester 2
        Route::post('/simpan', 'simpan')->name('simpan');

        // Reset satu pegawai / semua pegawai pada tahun terpilih
        Route::post('/reset', 'reset')->name('reset');
    });


// =========================================================
// PENILAIAN
// =========================================================
Route::prefix('kinerjapegawai-penilaian')
    ->name('admin.penilaian.')
    ->controller(PenilaianController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
        Route::post('/input', 'simpan')->name('store');
    });


// =========================================================
// MONITORING PELAPORAN
// =========================================================
Route::prefix('monitoring-pelaporan')
    ->name('admin.monitoring_pelaporan.')
    ->controller(MonitoringPelaporanController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
    });

Route::name('monitoring-pelaporan.')
    ->controller(MonitoringPelaporanController::class)
    ->group(function () {

        Route::get('/monitoring-pelaporan/download-pegawai/{pegawai}', 'downloadPegawai')->name('download-pegawai');
        Route::get('/monitoring-pelaporan/download-semua', 'downloadSemua')->name('download-semua');
        Route::post('/monitoring-pelaporan/reset', 'reset')->name('reset');
    });


// =========================================================
// MONITORING SKP
// =========================================================
Route::prefix('monitoring-skp')
    ->name('admin.monitoring_skp.')
    ->controller(MonitoringSkpController::class)
    ->group(function () {
        Route::get('/penetapan', 'index')->name('penetapan');
        Route::get('/evaluasi', 'evaluasi')->name('evaluasi');
        Route::get('/penetapan/download-all', 'downloadAllPenetapan')
            ->name('penetapan.download-all');
        Route::post('/penetapan/reset', 'resetPenetapan')
            ->name('penetapan.reset');
        Route::post('/penetapan/{skp}/review', 'reviewPenetapan')
            ->name('penetapan.review');
        Route::get('/evaluasi/download-all', 'downloadAllEvaluasi')
            ->name('evaluasi.download-all');
        Route::post('/evaluasi/reset', 'resetEvaluasi')
            ->name('evaluasi.reset');
    });


// =========================================================
// MITRA PENILAIAN
// =========================================================    
Route::prefix('mitra-penilaian')
    ->name('admin.mitra_penilaian.')
    ->controller(MitraPenilaianController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
    });



// =========================================================
// MITRA BLACKLIST
// =========================================================    
Route::prefix('mitra-blacklist')
    ->name('admin.mitra_blacklist.')
    ->controller(MitraBlacklistController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
    });



// =========================================================
// PENILAIAN TAHAP 1
// =========================================================
Route::prefix('sipeter-penilaian/penilaian-tahap1')
    ->name('admin.sipeter.penilaian.penilaian-tahap1.')
    ->controller(PenilaianTahap1Controller::class)
    ->group(function () {

        // Halaman utama / semua step
        Route::get('/', 'index')->name('index');

        // Step 1 -> Step 2
        Route::post('/proses', 'proses')->name('proses');

        // Simpan pengecualian
        Route::post('/pengecualian', 'simpanPengecualian')
            ->name('pengecualian.store');

        // Proses 5 pegawai terbaik
        Route::post('/proses-terbaik', 'prosesTerbaik')
            ->name('proses-terbaik');

        Route::post('/proses-final', 'prosesFinal')->name('proses-final');

        // Approval
        Route::post('/{id}/setujui', 'setujui')
            ->name('setujui');

        Route::post('/{id}/tolak', 'tolak')
            ->name('tolak');

        // Lihat riwayat
        Route::get('/{id}/lihat', 'lihat')
            ->name('lihat');

        // Hapus riwayat
        Route::delete('/{id}', 'destroy')
            ->name('destroy');
    });



// =========================================================
// SURAT KEPUTUSAN - PANITIA
// =========================================================
Route::prefix('sipeter-surat-keputusan/panitia')
    ->name('admin.sipeter.surat-keputusan.panitia.')
    ->controller(SuratkeputusanpanitiaController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::get('/{id}/lihat', 'show')->name('show');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });



// =========================================================
// SIPETER - MANAJEMEN PERTANYAAN
// =========================================================
Route::prefix('sipeter-manajemen-pertanyaan')
    ->name('admin.sipeter.manajemen-pertanyaan.')
    ->controller(SipeterPertanyaanController::class)
    ->group(function () {

        Route::get('/', 'index')
            ->name('index');

        Route::post('/', 'store')
            ->name('store');

        Route::put('/{pertanyaan}', 'update')
            ->name('update');

        Route::delete('/{pertanyaan}', 'destroy')
            ->name('destroy');
    });



// =========================================================
// PENILAIAN TAHAP 2 - PANITIA
// =========================================================
Route::prefix('sipeter-penilaian/penilaian-tahap2/panitia')
    ->name('admin.sipeter.penilaian.penilaian-tahap2.panitia.')
    ->controller(PenilaianTahap2PanitiaController::class)
    ->group(function () {

        // Halaman utama (Step 1, 2, 3)
        Route::get('/', 'index')->name('index');

        // Step 1 -> Step 2
        Route::post('/proses', 'proses')->name('proses');

        // Step 2 -> Step 3 (simpan pertanyaan terpilih)
        Route::post('/pertanyaan', 'simpanPertanyaan')->name('pertanyaan.store');

        // Step 3 -> mulai penilaian
        Route::post('/mulai', 'mulai')->name('mulai');
    });



// =========================================================
// SURAT KEPUTUSAN - HASIL
// =========================================================
Route::prefix('sipeter-surat-keputusan/hasil')
    ->name('admin.sipeter.surat-keputusan.hasil.')
    ->controller(SuratkeputusanhasilController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
    });

// =========================================================
// LOGIN
// =========================================================
Route::get('/login', function () {
    return view('auth.login');
})->name('login');