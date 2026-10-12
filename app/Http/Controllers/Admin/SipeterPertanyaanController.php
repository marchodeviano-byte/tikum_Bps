<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SipeterPertanyaan;
use Illuminate\Http\Request;

class SipeterPertanyaanController extends Controller
{
    /**
     * =========================================================
     * MANAJEMEN PERTANYAAN
     * =========================================================
     */
    public function index()
    {
        $pertanyaanList = SipeterPertanyaan::latest()->get();

        return view(
            'admin.sipeter.manajemen-pertanyaan.index',
            compact('pertanyaanList')
        );
    }

    /**
     * =========================================================
     * TAMBAH PERTANYAAN
     * =========================================================
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'pertanyaan' => [
                'required',
                'string',
                'max:5000',
            ],
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
            'pertanyaan.string' => 'Pertanyaan harus berupa teks.',
            'pertanyaan.max' => 'Pertanyaan maksimal 5000 karakter.',
        ]);

        SipeterPertanyaan::create([
            'pertanyaan' => trim($data['pertanyaan']),
        ]);

        return redirect()
            ->route('admin.sipeter.manajemen-pertanyaan.index')
            ->with(
                'success',
                'Pertanyaan berhasil ditambahkan.'
            );
    }

    /**
     * =========================================================
     * UPDATE PERTANYAAN
     * =========================================================
     */
    public function update(
        Request $request,
        SipeterPertanyaan $pertanyaan
    ) {
        $data = $request->validate([
            'pertanyaan' => [
                'required',
                'string',
                'max:5000',
            ],
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
            'pertanyaan.string' => 'Pertanyaan harus berupa teks.',
            'pertanyaan.max' => 'Pertanyaan maksimal 5000 karakter.',
        ]);

        $pertanyaan->update([
            'pertanyaan' => trim($data['pertanyaan']),
        ]);

        return redirect()
            ->route('admin.sipeter.manajemen-pertanyaan.index')
            ->with(
                'success',
                'Pertanyaan berhasil diperbarui.'
            );
    }

    /**
     * =========================================================
     * HAPUS PERTANYAAN
     * =========================================================
     */
    public function destroy(
        SipeterPertanyaan $pertanyaan
    ) {
        $pertanyaan->delete();

        return redirect()
            ->route('admin.sipeter.manajemen-pertanyaan.index')
            ->with(
                'success',
                'Pertanyaan berhasil dihapus.'
            );
    }
}
