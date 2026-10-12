<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user
     */
    public function index(Request $request)
    {
        $query = User::with(['jabatan', 'role']);

        // Search NIP / Nama
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        // Filter role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query
            ->orderBy('nama', 'asc')
            ->paginate(10)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        return view('admin.manajemen-user.index', compact(
            'users',
            'roles'
        ));
    }

    /**
     * Form tambah user
     */
    public function create()
    {
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        $roles = Role::orderBy('name')->get();

        return view('admin.manajemen-user.create', compact(
            'jabatans',
            'roles'
        ));
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => [
                'required',
                'digits:18',
                'unique:users,nip',
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'jabatan_id' => [
                'nullable',
                'exists:jabatans,id',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP harus terdiri dari 18 digit.',
            'nip.unique' => 'NIP sudah terdaftar.',

            'nama.required' => 'Nama wajib diisi.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',

            'jabatan_id.exists' => 'Jabatan yang dipilih tidak valid.',
            'role_id.required' => 'Role wajib dipilih.',
            'role_id.exists' => 'Role yang dipilih tidak valid.',
        ]);

        $validated['password'] = Hash::make(
            $validated['password']
        );

        User::create($validated);

        return redirect()
            ->route('admin.manajemen-user.index')
            ->with('success', 'Data user berhasil ditambahkan.');
    }

    /**
     * Detail user
     */
    public function show(User $manajemen_user)
    {
        $manajemen_user->load([
            'jabatan',
            'role',
        ]);

        return view(
            'admin.manajemen-user.show',
            compact('manajemen_user')
        );
    }

    /**
     * Form edit user
     */
    public function edit(User $manajemen_user)
    {
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        $roles = Role::orderBy('name')->get();

        return view(
            'admin.manajemen-user.edit',
            compact(
                'manajemen_user',
                'jabatans',
                'roles'
            )
        );
    }

    /**
     * Update user
     */
    public function update(
        Request $request,
        User $manajemen_user
    ) {
        $validated = $request->validate([
            'nip' => [
                'required',
                'digits:18',
                Rule::unique('users', 'nip')
                    ->ignore($manajemen_user->id),
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($manajemen_user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'jabatan_id' => [
                'nullable',
                'exists:jabatans,id',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP harus terdiri dari 18 digit.',
            'nip.unique' => 'NIP sudah digunakan.',

            'nama.required' => 'Nama wajib diisi.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',

            'jabatan_id.exists' => 'Jabatan tidak valid.',
            'role_id.required' => 'Role wajib dipilih.',
            'role_id.exists' => 'Role tidak valid.',
        ]);

        // Password hanya diubah kalau diisi
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make(
                $validated['password']
            );
        } else {
            unset($validated['password']);
        }

        $manajemen_user->update($validated);

        return redirect()
            ->route(
                'admin.manajemen-user.index',
                $manajemen_user
            )
            ->with(
                'success',
                'Data user berhasil diperbarui.'
            );
    }

    /**
     * Mengubah status user
     */
    public function destroy(User $manajemen_user)
    {
        $statusBaru = !$manajemen_user->status;

        $manajemen_user->update([
            'status' => $statusBaru,
        ]);

        $pesan = $statusBaru
            ? 'Akun pegawai berhasil diaktifkan kembali.'
            : 'Akun pegawai berhasil dinonaktifkan.';

        return redirect()
            ->route('admin.manajemen-user.index')
            ->with('success', $pesan);
    }
}
