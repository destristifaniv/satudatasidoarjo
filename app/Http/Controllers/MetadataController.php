<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\User; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetadataController extends Controller
{
    /**
     * Menampilkan daftar metadata dengan filter Tahun dan Search.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // 🔥 LOGIKA PENYAMBUNG: Ambil semua ID user yang satu instansi (OPD)
        if ($user->role === 'admin') {
            $query = Dataset::query();
        } else {
            // Cari semua ID user yang opd_name-nya sama dengan user yang sedang login
            $userIds = User::where('opd_name', $user->opd_name)->pluck('id');
            $query = Dataset::whereIn('user_id', $userIds);
        }

        // 2. Filter Tahun (Jika dipilih di dropdown)
        if ($request->filled('year') && $request->year !== 'all') {
            $query->where('year_start', $request->year);
        }

        // 3. Filter Pencarian (Nama atau Kode DSSD)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('dssd_code', 'like', '%' . $request->search . '%');
            });
        }

        // 4. Ambil data dengan pagination ringkas
        $datasets = $query->latest()->paginate(10)->withQueryString();

        return view('admin.metadata.index', compact('datasets'));
    }

    /**
     * Fungsi edit, update, dll tetap sama dengan pengecekan akses OPD
     */
    public function edit($id)
    {
        // Pastikan hanya bisa edit data milik instansinya sendiri
        $userIds = User::where('opd_name', auth()->user()->opd_name)->pluck('id');
        $dataset = Dataset::whereIn('user_id', $userIds)->findOrFail($id);
        
        return view('admin.metadata.edit', compact('dataset'));
    }

    public function update(Request $request, $id)
    {
        $userIds = User::where('opd_name', auth()->user()->opd_name)->pluck('id');
        $dataset = Dataset::whereIn('user_id', $userIds)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'unit' => 'required|string',
            'frequency' => 'required|string',
            'tags' => 'required|string',
        ]);

        $dataset->update($request->all());

        return redirect()->route('admin.metadata')->with('success', 'Metadata Dataset Berhasil Disempurnakan!');
    }
}