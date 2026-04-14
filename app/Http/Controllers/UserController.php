<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan halaman daftar user
    public function index()
    {
        // Kunci Pintu: Cek apakah yang login adalah 'admin'
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak! Halaman ini hanya untuk Admin Pusat.');
        }

        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    // Menyimpan user baru ke database
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') return back();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,opd,pimpinan',
            'opd_name' => 'nullable|string|max:255', // Kosong jika role-nya admin
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password dienkripsi otomatis
            'role' => $request->role,
            'opd_name' => $request->opd_name,
        ]);

        return redirect()->back()->with('success', 'Akun berhasil ditambahkan!');
    }

    // Menghapus user
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') return back();
        
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Akun berhasil dihapus!');
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') return back();

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id, // Boleh email sama asal punya dia sendiri
            'role' => 'required|in:admin,opd,pimpinan',
            'opd_name' => 'nullable|string|max:255',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->opd_name = $request->opd_name;

        // Jika form password diisi, maka update passwordnya. Jika kosong, biarkan password lama.
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data akun berhasil diperbarui!');
    }
}