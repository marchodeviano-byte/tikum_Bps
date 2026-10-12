<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratKeputusanPanitia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratkeputusanpanitiaController extends Controller
{
    /**
     * ============================================================
     * INDEX
     * ============================================================
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $data = SuratKeputusanPanitia::with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('no_sk', 'like', '%' . $search . '%')
                        ->orWhere('perihal', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('nama', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.sipeter.surat-keputusan.panitia.index',
            compact('data', 'search')
        );
    }

    /**
     * ============================================================
     * STORE
     * Upload SK baru
     * ============================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_sk' => [
                'required',
                'string',
                'max:150',
            ],

            'tanggal_sk' => [
                'required',
                'date',
            ],

            'perihal' => [
                'required',
                'string',
                'max:255',
            ],

            'file_pdf' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ], [
            'no_sk.required' => 'Nomor SK wajib diisi.',
            'tanggal_sk.required' => 'Tanggal SK wajib diisi.',
            'tanggal_sk.date' => 'Format tanggal SK tidak valid.',
            'perihal.required' => 'Perihal wajib diisi.',

            'file_pdf.required' => 'File PDF wajib diupload.',
            'file_pdf.file' => 'File yang diupload tidak valid.',
            'file_pdf.mimes' => 'File harus berformat PDF.',
            'file_pdf.max' => 'Ukuran file PDF maksimal 10 MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Tentukan user yang mengupload
        |--------------------------------------------------------------------------
        |
        | Untuk sementara masih ada fallback ke user aktif pertama
        | apabila sistem login belum digunakan.
        |
        */
        $userId = Auth::id();

        if (!$userId) {
            $userId = User::where('status', true)
                ->orderBy('id')
                ->value('id');
        }

        if (!$userId) {
            return back()
                ->withInput()
                ->with('error', 'Tidak ada user yang dapat digunakan sebagai pemilik SK.');
        }

        /*
        |--------------------------------------------------------------------------
        | Upload PDF
        |--------------------------------------------------------------------------
        */

        $file = $request->file('file_pdf');

        /*
        | Nama file dibuat benar-benar unik.
        | Ini penting supaya PDF baru tidak tertukar dengan PDF lama.
        */
        $namaFile = 'SK_Panitia_'
            . date('YmdHis')
            . '_'
            . uniqid()
            . '.pdf';

        $path = $file->storeAs(
            'surat-keputusan/panitia',
            $namaFile,
            'public'
        );

        /*
        |--------------------------------------------------------------------------
        | Simpan ke database
        |--------------------------------------------------------------------------
        */
        SuratKeputusanPanitia::create([
            'user_id' => $userId,
            'no_sk' => $request->no_sk,
            'tanggal_sk' => $request->tanggal_sk,
            'perihal' => $request->perihal,
            'file_pdf' => $path,
        ]);

        return redirect()
            ->route('admin.sipeter.surat-keputusan.panitia.index')
            ->with('success', 'Surat Keputusan berhasil diupload.');
    }

    /**
     * ============================================================
     * EDIT
     * ============================================================
     */
    public function edit($id)
    {
        $data = SuratKeputusanPanitia::with('user')
            ->findOrFail($id);

        return view(
            'admin.sipeter.surat-keputusan.panitia.edit',
            compact('data')
        );
    }

    /**
     * ============================================================
     * UPDATE
     * ============================================================
     *
     * Bisa mengganti:
     * - Nomor SK
     * - Tanggal SK
     * - Perihal
     * - File PDF
     */
    public function update(Request $request, $id)
    {
        $data = SuratKeputusanPanitia::findOrFail($id);

        $request->validate([
            'no_sk' => [
                'required',
                'string',
                'max:150',
            ],

            'tanggal_sk' => [
                'required',
                'date',
            ],

            'perihal' => [
                'required',
                'string',
                'max:255',
            ],

            'file_pdf' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ], [
            'no_sk.required' => 'Nomor SK wajib diisi.',
            'tanggal_sk.required' => 'Tanggal SK wajib diisi.',
            'tanggal_sk.date' => 'Format tanggal SK tidak valid.',
            'perihal.required' => 'Perihal wajib diisi.',

            'file_pdf.file' => 'File yang diupload tidak valid.',
            'file_pdf.mimes' => 'File harus berformat PDF.',
            'file_pdf.max' => 'Ukuran file PDF maksimal 10 MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update data teks
        |--------------------------------------------------------------------------
        */

        $data->no_sk = $request->no_sk;
        $data->tanggal_sk = $request->tanggal_sk;
        $data->perihal = $request->perihal;

        /*
        |--------------------------------------------------------------------------
        | Jika user mengupload PDF baru
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file_pdf')) {

            $fileBaru = $request->file('file_pdf');

            /*
            |--------------------------------------------------------------------------
            | 1. Hapus PDF lama
            |--------------------------------------------------------------------------
            */

            if (
                !empty($data->file_pdf) &&
                Storage::disk('public')->exists($data->file_pdf)
            ) {
                Storage::disk('public')->delete($data->file_pdf);
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Buat nama file BARU
            |--------------------------------------------------------------------------
            |
            | Jangan menggunakan nama file lama.
            | Dengan nama baru, browser juga tidak akan menganggap
            | PDF tersebut sebagai file yang sama.
            |
            */

            $namaFileBaru = 'SK_Panitia_'
                . date('YmdHis')
                . '_'
                . uniqid()
                . '.pdf';

            /*
            |--------------------------------------------------------------------------
            | 3. Simpan PDF baru
            |--------------------------------------------------------------------------
            */

            $pathBaru = $fileBaru->storeAs(
                'surat-keputusan/panitia',
                $namaFileBaru,
                'public'
            );

            /*
            |--------------------------------------------------------------------------
            | 4. Update path PDF di database
            |--------------------------------------------------------------------------
            */

            $data->file_pdf = $pathBaru;
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan semua perubahan
        |--------------------------------------------------------------------------
        */

        $data->save();

        return redirect()
            ->route('admin.sipeter.surat-keputusan.panitia.index')
            ->with('success', 'Surat Keputusan berhasil diperbarui.');
    }

    /**
     * ============================================================
     * SHOW / LIHAT PDF
     * ============================================================
     */
    public function show($id)
    {
        $data = SuratKeputusanPanitia::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Pastikan database memiliki path file
        |--------------------------------------------------------------------------
        */

        if (empty($data->file_pdf)) {
            abort(404, 'File PDF tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan file benar-benar ada
        |--------------------------------------------------------------------------
        */

        if (!Storage::disk('public')->exists($data->file_pdf)) {
            abort(404, 'File PDF tidak ditemukan di penyimpanan.');
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil physical path
        |--------------------------------------------------------------------------
        */

        $filePath = storage_path(
            'app/public/' . $data->file_pdf
        );

        if (!file_exists($filePath)) {
            abort(404, 'File PDF tidak ditemukan di server.');
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil nama file
        |--------------------------------------------------------------------------
        */

        $namaFile = basename($data->file_pdf);

        /*
        |--------------------------------------------------------------------------
        | Tampilkan PDF langsung di browser
        |--------------------------------------------------------------------------
        |
        | Cache dinonaktifkan supaya ketika PDF diganti,
        | browser tidak menggunakan versi PDF sebelumnya.
        |
        */

        return response()->file(
            $filePath,
            [
                'Content-Type' => 'application/pdf',

                'Content-Disposition' =>
                'inline; filename="' . $namaFile . '"',

                'Cache-Control' =>
                'no-cache, no-store, must-revalidate, max-age=0',

                'Pragma' => 'no-cache',

                'Expires' => '0',
            ]
        );
    }

    /**
     * ============================================================
     * DESTROY / HAPUS
     * ============================================================
     */
    public function destroy($id)
    {
        $data = SuratKeputusanPanitia::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Hapus file PDF dari storage
        |--------------------------------------------------------------------------
        */

        if (
            !empty($data->file_pdf) &&
            Storage::disk('public')->exists($data->file_pdf)
        ) {
            Storage::disk('public')->delete($data->file_pdf);
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus data dari database
        |--------------------------------------------------------------------------
        */

        $data->delete();

        return redirect()
            ->route('admin.sipeter.surat-keputusan.panitia.index')
            ->with('success', 'Surat Keputusan berhasil dihapus.');
    }
}