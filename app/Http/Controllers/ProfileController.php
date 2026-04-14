<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Pastikan ini ada untuk kelola file
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil organisasi dengan statistik dinamis instansi.
     */
    public function index(): View
    {
        $user = Auth::user();

        // 🔥 LOGIKA PENYAMBUNG: Hitung total dataset berdasarkan instansi (OPD), bukan user personal
        if ($user->role === 'admin') {
            $totalDataset = Dataset::count();
            $organization = $user;
        } else {
            // Ambil semua ID user dalam instansi yang sama
            $userIds = User::where('opd_name', $user->opd_name)->pluck('id');
            $totalDataset = Dataset::whereIn('user_id', $userIds)->count();

            // Ambil data profil dari user pertama di instansi ini yang profilnya mungkin sudah lengkap
            // atau tetap gunakan user yang sedang login
            $organization = $user; 
        }

        return view('admin.organizations.index', [
            'user' => $organization,
            'totalDataset' => $totalDataset
        ]);
    }

    /**
     * Menampilkan form edit profil organisasi.
     */
    public function edit(Request $request): View
    {
        return view('admin.organizations.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Menyimpan perubahan profil organisasi (TERMASUK FOTO & DESKRIPSI).
     */
    public function update_organization(Request $request)
    {
        // 1. Validasi input, tambahkan aturan untuk avatar dan deskripsi
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'description' => 'nullable|string',
            'avatar'      => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // Maksimal ukuran 2MB
        ]);

        $user = Auth::user();

        // 2. Siapkan keranjang data dasar
        $dataToUpdate = [
            'name'        => $request->name,
            'email'       => $request->email,
            'description' => $request->description,
        ];

        // 3. Logika Ajaib untuk menyimpan File Foto
        if ($request->hasFile('avatar')) {
            // Hapus foto lama dari folder penyimpanan jika sudah pernah upload
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Simpan foto baru ke folder storage/app/public/avatars
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $dataToUpdate['avatar'] = $avatarPath;
        }

        // 4. Simpan semua perubahan ke database
        $user->update($dataToUpdate);

        return redirect()->route('admin.organizations')->with('success', 'Profil Instansi Berhasil Diperbarui!');
    }
}