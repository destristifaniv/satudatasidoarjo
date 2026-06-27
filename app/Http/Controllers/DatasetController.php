<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DatasetController extends Controller
{
    /**
     * Menampilkan Dashboard Admin dengan statistik berdasarkan Instansi (OPD)
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();

        // LOGIKA AJAIB PENYAMBUNG DATA
        if ($user->role === 'admin') {
            $userIds = User::pluck('id'); 
        } else {
            // Gabungkan nama instansi dari opd_name atau name (jika akun lama)
            $instansi = $user->opd_name ?: $user->name;
            $userIds = User::where('opd_name', $instansi)
                           ->orWhere('name', $instansi)
                           ->pluck('id');
        }

        // 1. HITUNG TAGS UNIK
        $userDatasets = Dataset::whereIn('user_id', $userIds)->whereNotNull('tags')->pluck('tags');
        $allTags = [];
        foreach ($userDatasets as $tagString) {
            $tagsArray = array_map('trim', explode(',', $tagString));
            $allTags = array_merge($allTags, $tagsArray);
        }
        $uniqueTagsCount = count(array_unique(array_filter($allTags)));

        // 2. KUMPULKAN STATISTIK UTAMA
        $stats = [
            'total_dataset' => Dataset::whereIn('user_id', $userIds)->count(),
            'total_unduhan' => Dataset::whereIn('user_id', $userIds)->sum('downloads'),
            'total_tags'    => $uniqueTagsCount,
        ];

        // 3. LOGIKA GRAFIK BATANG
        $filter = $request->get('filter', 'tahun');
        $uploadStats = [];

        if ($filter == 'minggu') {
            $startOfWeek = now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
                $uploadStats[] = Dataset::whereIn('user_id', $userIds)
                    ->whereDate('created_at', $date->toDateString())
                    ->count();
            }
        } elseif ($filter == 'bulan') {
            $startOfMonth = now()->startOfMonth();
            for ($i = 0; $i < 4; $i++) {
                $start = $startOfMonth->copy()->addWeeks($i);
                $end = $start->copy()->endOfWeek();
                $uploadStats[] = Dataset::whereIn('user_id', $userIds)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
            }
        } else {
            for ($m = 1; $m <= 12; $m++) {
                $uploadStats[] = Dataset::whereIn('user_id', $userIds)
                    ->whereYear('created_at', date('Y'))
                    ->whereMonth('created_at', $m)
                    ->count();
            }
        }

        // 4. HITUNG RATA-RATA KELENGKAPAN METADATA
        $totalDatasets = $stats['total_dataset'];
        $avgMetadata = 0;

        if ($totalDatasets > 0) {
            $datasets = Dataset::whereIn('user_id', $userIds)->get();
            $totalPercentage = 0;

            foreach ($datasets as $data) {
                $filledFields = 0;
                if (!empty($data->dssd_code)) $filledFields++;
                if (!empty($data->description)) $filledFields++;
                if (!empty($data->tags)) $filledFields++;
                if (!empty($data->unit)) $filledFields++;
                if (!empty($data->frequency)) $filledFields++;

                $totalPercentage += ($filledFields / 5) * 100;
            }
            $avgMetadata = round($totalPercentage / $totalDatasets);
        }

        return view('admin.dashboard', compact('stats', 'uploadStats', 'avgMetadata'));
    }

    /**
     * Menampilkan daftar dataset (Tabel Kelola Dataset)
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $query = Dataset::query();
        } else {
            $instansi = $user->opd_name ?: $user->name;
            $userIds = User::where('opd_name', $instansi)
                           ->orWhere('name', $instansi)
                           ->pluck('id');
            $query = Dataset::whereIn('user_id', $userIds);
        }

        if ($request->filled('year') && $request->year !== 'all') {
            $query->where('year_start', $request->year);
        }

        // SEARCH LAMA TETAP DIPERTAHANKAN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('dssd_code', 'like', '%' . $search . '%')
                  ->orWhere('tags', 'like', '%' . $search . '%')
                  ->orWhere('unit', 'like', '%' . $search . '%');
            });
        }

        // Search dari halaman publik dataset menggunakan name="q"
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%')
                  ->orWhere('dssd_code', 'like', '%' . $keyword . '%')
                  ->orWhere('tags', 'like', '%' . $keyword . '%')
                  ->orWhere('unit', 'like', '%' . $keyword . '%');
            });
        }

        // Filter organisasi
        if ($request->filled('org')) {
            $query->where('user_id', $request->org);
        }

        // Filter format file
        if ($request->filled('format')) {
            $query->where('file_path', 'like', '%.' . $request->format);
        }

        // Filter status metadata
        if ($request->filled('metadata_status')) {
            if ($request->metadata_status === 'lengkap') {
                $query->whereNotNull('dssd_code')
                      ->where('dssd_code', '!=', '')
                      ->whereNotNull('description')
                      ->where('description', '!=', '')
                      ->whereNotNull('tags')
                      ->where('tags', '!=', '')
                      ->whereNotNull('unit')
                      ->where('unit', '!=', '')
                      ->whereNotNull('frequency')
                      ->where('frequency', '!=', '');
            } elseif ($request->metadata_status === 'belum_lengkap') {
                $query->where(function($q) {
                    $q->whereNull('dssd_code')
                      ->orWhere('dssd_code', '')
                      ->orWhereNull('description')
                      ->orWhere('description', '')
                      ->orWhereNull('tags')
                      ->orWhere('tags', '')
                      ->orWhereNull('unit')
                      ->orWhere('unit', '')
                      ->orWhereNull('frequency')
                      ->orWhere('frequency', '');
                });
            }
        }

        // Urutkan dataset
        if ($request->filled('sort')) {
            if ($request->sort === 'terlama') {
                $query->oldest();
            } elseif ($request->sort === 'download') {
                $query->orderByDesc('downloads');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $datasets = $query->paginate(10)->withQueryString();

        return view('admin.manage-dataset', compact('datasets'));
    }

    public function create() 
    { 
        // FITUR AUTOCOMPLETE DSSD
        // Mengambil daftar DSSD dan Nama yang sudah ada di database untuk dijadikan rekomendasi
        $masterDatasets = Dataset::select('dssd_code', 'name')
                            ->distinct('dssd_code')
                            ->get();

        return view('admin.upload.dataset', compact('masterDatasets')); 
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $instansi = $user->opd_name ?: $user->name;
        $userIds = User::where('opd_name', $instansi)->orWhere('name', $instansi)->pluck('id');

        $request->validate([
            'dssd_code' => [
                'required', 
                Rule::unique('datasets')->where(fn ($q) => 
                    $q->where('year_start', $request->year_start)
                    ->whereIn('user_id', $userIds)
                )
            ],
            'name' => 'required|string|max:255',
            'dataset_file' => 'required|file|max:51200', 
            'category' => 'required', 
            'year_start' => 'required|integer', 
            'year_end' => 'required|integer',
            'tags' => 'required',
        ], [
            'dssd_code.unique' => 'Kode DSSD ini sudah digunakan oleh instansi Anda untuk tahun tersebut.'
        ]);

        $filePath = $request->hasFile('dataset_file') 
            ? $request->file('dataset_file')->storeAs('datasets', time() . '_' . $request->file('dataset_file')->getClientOriginalName(), 'public') 
            : null;

        Dataset::create([
            'dssd_code' => $request->dssd_code,
            'name' => $request->name,
            'description' => $request->description,
            'file_path' => $filePath,
            'category' => $request->category,
            'organization' => $instansi,
            'tags' => $request->tags,
            'unit' => $request->unit,
            'frequency' => $request->frequency,
            'level' => $request->level,
            'year_start' => $request->year_start,
            'year_end' => $request->year_end,
            'user_id' => Auth::id(),
            'downloads' => 0,
        ]);

        return redirect()->route('admin.manage-dataset')->with('success', 'Data berhasil ditambahkan dan menunggu verifikasi Pimpinan!');
    }

    public function edit($id) 
    { 
        $user = auth()->user();
        $instansi = $user->opd_name ?: $user->name;
        $userIds = User::where('opd_name', $instansi)->orWhere('name', $instansi)->pluck('id');

        $dataset = Dataset::whereIn('user_id', $userIds)->findOrFail($id); 
        return view('admin.upload.edit', compact('dataset')); 
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $instansi = $user->opd_name ?: $user->name;
        $userIds = User::where('opd_name', $instansi)->orWhere('name', $instansi)->pluck('id');
        
        $dataset = Dataset::whereIn('user_id', $userIds)->findOrFail($id);
        
        $request->validate([
            'dssd_code' => [
                'required', 
                Rule::unique('datasets')->where(fn ($q) => 
                    $q->where('year_start', $request->year_start)
                    ->whereIn('user_id', $userIds) 
                )->ignore($dataset->id) 
            ],
            'name' => 'required|string|max:255',
            'category' => 'required', 
            'year_start' => 'required|integer', 
            'year_end' => 'required|integer',
            'tags' => 'required',
        ]);

        if ($request->hasFile('dataset_file')) {
            if ($dataset->file_path) { 
                Storage::disk('public')->delete($dataset->file_path); 
            }
            $dataset->file_path = $request->file('dataset_file')->storeAs('datasets', time() . '_' . $request->file('dataset_file')->getClientOriginalName(), 'public');
        }

        $dataset->update([
            'dssd_code' => $request->dssd_code,
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'tags' => $request->tags,
            'unit' => $request->unit,
            'frequency' => $request->frequency,
            'level' => $request->level,
            'year_start' => $request->year_start,
            'year_end' => $request->year_end,
            'status' => 'pending', 
            'feedback' => null
        ]);
        
        return redirect()->route('admin.manage-dataset')->with('success', 'Dataset berhasil diperbarui dan dikirim ulang untuk verifikasi!');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $instansi = $user->opd_name ?: $user->name;
        $userIds = User::where('opd_name', $instansi)->orWhere('name', $instansi)->pluck('id');

        $dataset = Dataset::whereIn('user_id', $userIds)->findOrFail($id);

        if ($dataset->file_path) { Storage::disk('public')->delete($dataset->file_path); }
        $dataset->delete();
        return redirect()->back()->with('success', 'Dataset Berhasil Dihapus!');
    }

    public function download($id) 
    { 
        $dataset = Dataset::findOrFail($id); 
        $dataset->increment('downloads'); 
        return Storage::disk('public')->download($dataset->file_path); 
    }

    public function manage(Request $request) 
    { 
        return $this->index($request); 
    }
    
    public function show($id)
    {
        $dataset = Dataset::with('user')->findOrFail($id);
        return view('dataset.show', compact('dataset'));
    }

    public function organizationDetail($id)
    {
        $organization = User::withCount('datasets')->findOrFail($id);

        $vol2022 = Dataset::where('user_id', $id)->where('year_start', 2022)->count();
        $vol2023 = Dataset::where('user_id', $id)->where('year_start', 2023)->count();
        $vol2024 = Dataset::where('user_id', $id)->where('year_start', 2024)->count();

        $growth = ($vol2022 > 0) ? (($vol2024 - $vol2022) / $vol2022) * 100 : 0;
        
        $mostUsedUnit = Dataset::where('user_id', $id)
            ->select('unit', DB::raw('count(*) as total'))
            ->groupBy('unit')
            ->orderByDesc('total')
            ->first();

        $units2022 = Dataset::where('user_id', $id)->where('year_start', 2022)
            ->select('unit', DB::raw('count(*) as total'))->groupBy('unit')->get();

        $units2023 = Dataset::where('user_id', $id)->where('year_start', 2023)
            ->select('unit', DB::raw('count(*) as total'))->groupBy('unit')->get();

        $units2024 = Dataset::where('user_id', $id)->where('year_start', 2024)
            ->select('unit', DB::raw('count(*) as total'))->groupBy('unit')->get();

        $trend2022 = Dataset::where('user_id', $id)->where('year_start', 2022)->distinct('dssd_code')->count();
        $trend2023 = Dataset::where('user_id', $id)->where('year_start', 2023)->distinct('dssd_code')->count();
        $trend2024 = Dataset::where('user_id', $id)->where('year_start', 2024)->distinct('dssd_code')->count();

        $activities2022 = Dataset::where('user_id', $id)->where('year_start', 2022)->pluck('id', 'name')->toArray();
        $activities2023 = Dataset::where('user_id', $id)->where('year_start', 2023)->pluck('id', 'name')->toArray();
        $activities2024 = Dataset::where('user_id', $id)->where('year_start', 2024)->pluck('id', 'name')->toArray();
        
        $allActivityNames = array_unique(array_merge(
            array_keys($activities2022), 
            array_keys($activities2023), 
            array_keys($activities2024)
        ));

        return view('organizations.detail', compact(
            'organization', 
            'vol2022', 'vol2023', 'vol2024', 
            'growth', 'mostUsedUnit',
            'units2022', 'units2023', 'units2024', 
            'trend2022', 'trend2023', 'trend2024',
            'allActivityNames', 'activities2022', 'activities2023', 'activities2024'
        ));
    }

    public function verify(Request $request, $id)
    {
        if (auth()->user()->role !== 'pimpinan') {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'feedback' => 'nullable|string'
        ]);

        $dataset = Dataset::findOrFail($id);
        $dataset->status = $request->status;
        $dataset->feedback = $request->status == 'rejected' ? $request->feedback : null; 
        $dataset->save();

        $pesan = $request->status == 'approved' ? 'Dataset disetujui!' : 'Dataset dikembalikan untuk direvisi.';
        return redirect()->back()->with('success', $pesan);
    }

    /**
     * API JSON Dataset
     * Digunakan agar dataset dapat dikonsumsi oleh platform lain
     */
    public function apiIndex(Request $request)
    {
        $query = Dataset::with('user');

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%')
                  ->orWhere('dssd_code', 'like', '%' . $keyword . '%')
                  ->orWhere('tags', 'like', '%' . $keyword . '%')
                  ->orWhere('unit', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('org')) {
            $query->where('user_id', $request->org);
        }

        if ($request->filled('year')) {
            $query->where('year_start', $request->year);
        }

        if ($request->filled('format')) {
            $query->where('file_path', 'like', '%.' . $request->format);
        }

        if ($request->filled('metadata_status')) {
            if ($request->metadata_status === 'lengkap') {
                $query->whereNotNull('dssd_code')
                      ->where('dssd_code', '!=', '')
                      ->whereNotNull('description')
                      ->where('description', '!=', '')
                      ->whereNotNull('tags')
                      ->where('tags', '!=', '')
                      ->whereNotNull('unit')
                      ->where('unit', '!=', '')
                      ->whereNotNull('frequency')
                      ->where('frequency', '!=', '');
            } elseif ($request->metadata_status === 'belum_lengkap') {
                $query->where(function($q) {
                    $q->whereNull('dssd_code')
                      ->orWhere('dssd_code', '')
                      ->orWhereNull('description')
                      ->orWhere('description', '')
                      ->orWhereNull('tags')
                      ->orWhere('tags', '')
                      ->orWhereNull('unit')
                      ->orWhere('unit', '')
                      ->orWhereNull('frequency')
                      ->orWhere('frequency', '');
                });
            }
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'terlama') {
                $query->oldest();
            } elseif ($request->sort === 'download') {
                $query->orderByDesc('downloads');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $datasets = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Data dataset berhasil diambil',
            'total' => $datasets->total(),
            'current_page' => $datasets->currentPage(),
            'last_page' => $datasets->lastPage(),
            'per_page' => $datasets->perPage(),
            'filters' => [
                'q' => $request->q,
                'org' => $request->org,
                'year' => $request->year,
                'format' => $request->format,
                'metadata_status' => $request->metadata_status,
                'sort' => $request->sort,
            ],
            'data' => $datasets->map(function ($dataset) {
                return [
                    'id' => $dataset->id,
                    'judul' => $dataset->name,
                    'deskripsi' => $dataset->description,
                    'organisasi' => $dataset->organization ?? optional($dataset->user)->opd_name ?? optional($dataset->user)->name,
                    'kategori' => $dataset->category,
                    'tahun_awal' => $dataset->year_start,
                    'tahun_akhir' => $dataset->year_end,
                    'format_file' => strtoupper(pathinfo($dataset->file_path, PATHINFO_EXTENSION)),
                    'jumlah_download' => $dataset->downloads ?? 0,
                    'tags' => $dataset->tags,
                    'metadata' => [
                        'kode_dssd' => $dataset->dssd_code,
                        'satuan' => $dataset->unit,
                        'frekuensi' => $dataset->frequency,
                        'level' => $dataset->level,
                    ],
                    'status' => $dataset->status,
                    'tanggal_upload' => optional($dataset->created_at)->format('Y-m-d H:i:s'),
                    'tanggal_update' => optional($dataset->updated_at)->format('Y-m-d H:i:s'),
                ];
            }),
        ]);
    }
}