<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Tampilkan daftar pengguna dengan filter & pencarian.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->get('status')) {
            $query->where('is_active', $status === 'active');
        }

        $users = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Tampilkan form tambah pengguna.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan pengguna baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'role'       => ['required', Rule::in(['admin', 'pimpinan', 'super_admin'])],
            'jabatan'    => ['nullable', 'string', 'max:255'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit pengguna.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Perbarui data pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'       => ['required', Rule::in(['admin', 'pimpinan', 'super_admin'])],
            'jabatan'    => ['nullable', 'string', 'max:255'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        // Jangan izinkan admin mengubah role/status akun sendiri lewat form edit
        if ($user->id === auth()->id()) {
            $validated['role']      = $user->role;
            $validated['is_active'] = $user->is_active;
        } else {
            $validated['is_active'] = $request->boolean('is_active', $user->is_active);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Hapus pengguna.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Pengguna \"{$name}\" berhasil dihapus.");
    }

    /**
     * Ubah role pengguna (admin <-> pimpinan) dari dropdown aksi cepat.
     */
    public function updateRole(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Anda tidak dapat mengubah role akun sendiri.');
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'pimpinan', 'super_admin'])],
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Role \"{$user->name}\" berhasil diubah menjadi " . ucfirst(str_replace('_', ' ', $validated['role'])) . ".");
    }

    /**
     * Aktifkan / nonaktifkan pengguna.
     */
    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Pengguna \"{$user->name}\" berhasil {$status}.");
    }

    /**
     * Reset password pengguna oleh admin.
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Password \"{$user->name}\" berhasil direset.");
    }
}