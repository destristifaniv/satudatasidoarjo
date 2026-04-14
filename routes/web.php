<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\MetadataController;
use Illuminate\Support\Facades\Route;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Public Routes (Halaman Depan Untuk Pengunjung)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // 1. KUNCI: Hanya hitung kelengkapan untuk data yang sudah APPROVED
    $allDatasets = Dataset::where('status', 'approved')->get();
    $totalDatasets = $allDatasets->count();
    $totalPercentage = 0;

    if ($totalDatasets > 0) {
        foreach ($allDatasets as $data) {
            $filledFields = 0;
            // Cek 5 indikator metadata
            if (!empty($data->dssd_code)) $filledFields++;
            if (!empty($data->description)) $filledFields++;
            if (!empty($data->tags)) $filledFields++;
            if (!empty($data->unit)) $filledFields++;
            if (!empty($data->frequency)) $filledFields++;

            // Tiap isian bernilai 20%
            $totalPercentage += ($filledFields / 5) * 100;
        }
        $avgVerified = round($totalPercentage / $totalDatasets);
    } else {
        $avgVerified = 0;
    }

    // 2. KUNCI: Statistik hanya menampilkan data APPROVED & Filter Role OPD
    $stats = [
        'total_dataset'      => $totalDatasets,
        
        // Hitung berdasarkan opd_name yang unik, karena 1 OPD bisa punya 1 Staf dan 1 Kadis. 
        // Kita hitung jumlah instansinya, bukan jumlah orangnya.
        'organisasi_opd'     => User::whereNotNull('opd_name')
                                    ->where('opd_name', '!=', '')
                                    ->distinct('opd_name')
                                    ->count('opd_name'), 

        'total_download'     => Dataset::where('status', 'approved')->sum('downloads'),
        'data_terverifikasi' => $avgVerified 
    ];

    // Mengambil 10 data terbaru yang sudah APPROVED untuk kartu dataset di halaman depan
    $latest_datasets = Dataset::where('status', 'approved')->with('user')->latest()->take(10)->get();

    $top_organisasi = User::where('role', 'opd')
        ->withCount(['datasets' => function($query) {
            $query->where('status', 'approved');
        }])
        ->orderBy('datasets_count', 'desc')
        ->take(3)
        ->get()
        ->map(function($user, $index) {
            return [
                'rank'     => $index + 1,
                'id'       => $user->id, 
                'name'     => $user->name,
                'datasets' => $user->datasets_count,
            ];
        });

    // ========================================================================
    // DATA UNTUK VISUALISASI GRAFIK / CHART DI HALAMAN DEPAN
    // ========================================================================
    
    // A. Data untuk Bar Chart (Top 5 OPD)
    $topOpdsForChart = User::where('role', 'opd')
        ->withCount(['datasets' => function($query) {
            $query->where('status', 'approved');
        }])->orderBy('datasets_count', 'desc')->take(5)->get();
    
    $opdNames = $topOpdsForChart->pluck('name')->map(function($name) {
        return \Illuminate\Support\Str::limit($name, 15); 
    })->toArray();
    $opdCounts = $topOpdsForChart->pluck('datasets_count')->toArray();
    $insightOpd = $topOpdsForChart->first()->name ?? 'Belum ada data';

    // B. Data untuk Doughnut Chart (Distribusi Satuan Data)
    $unitDistribution = Dataset::where('status', 'approved')
        ->select('unit', DB::raw('count(*) as total'))
        ->whereNotNull('unit')
        ->where('unit', '!=', '')
        ->groupBy('unit')
        ->orderBy('total', 'desc')
        ->take(5)
        ->get();
    $unitLabels = $unitDistribution->pluck('unit')->toArray();
    $unitCounts = $unitDistribution->pluck('total')->toArray();
    $insightUnit = $unitDistribution->first()->unit ?? 'Belum ada data';

    // C. Data untuk Line Chart (Tren Dataset per Tahun)
    $trendData = Dataset::where('status', 'approved')
        ->select('year_start', DB::raw('count(*) as total'))
        ->whereNotNull('year_start')
        ->groupBy('year_start')
        ->orderBy('year_start', 'asc')
        ->take(5)
        ->get();
    $trendYears = $trendData->pluck('year_start')->toArray();
    $trendCounts = $trendData->pluck('total')->toArray();

    // ========================================================================
    // DATA UNTUK PETA KECAMATAN (DIPERBAIKI 100% SAMA DENGAN HALAMAN DETAIL)
    // ========================================================================
    $kecamatanDataMap = User::where('role', 'opd')
        ->where('name', 'like', '%Kecamatan%')
        ->with('datasets') // Load semua dataset (tanpa filter approved, sama seperti Controller)
        ->get()
        ->mapWithKeys(function ($item) {
            $name = strtoupper($item->name);
            $cleanName = trim(str_replace('KECAMATAN ', '', str_replace('KECAMATAN', '', $name)));
            
            $datasets = $item->datasets;
            
            // Total Dataset keseluruhan
            $count = $datasets->count();
            
            // Logika persis dari Controller: organizationDetail
            $vol2022 = $datasets->where('year_start', 2022)->count();
            $vol2024 = $datasets->where('year_start', 2024)->count();
            
            // 1. Layanan adalah jumlah dataset tahun 2024
            $layanan = $vol2024;
            
            // 2. Pertumbuhan: membandingkan 2024 dengan 2022
            $growth = ($vol2022 > 0) ? (($vol2024 - $vol2022) / $vol2022) * 100 : 0;
            $pertumbuhan = round($growth, 1) . '%';

            // 3. Cari satuan terbanyak (sama persis query di Controller)
            $satuanArray = $datasets->whereNotNull('unit')->where('unit', '!=', '')->pluck('unit')->toArray();
            $satuan = '-';
            if (!empty($satuanArray)) {
                $counts = array_count_values($satuanArray);
                arsort($counts);
                $satuan = key($counts); 
            }
            
            return [$cleanName => [
                'count'            => $count,
                'layanan'          => $layanan,
                'pertumbuhan'      => $pertumbuhan,
                'satuan_terbanyak' => $satuan,
                'id'               => $item->id
            ]];
        })->toArray();

    return view('welcome', compact(
        'stats', 'latest_datasets', 'top_organisasi',
        'opdNames', 'opdCounts', 'insightOpd',
        'unitLabels', 'unitCounts', 'insightUnit',
        'trendYears', 'trendCounts',
        'kecamatanDataMap'
    ));
})->name('home');

Route::get('/datasets', function () {
    $stats = [
        'total_dataset'  => Dataset::where('status', 'approved')->count(),
        'organisasi_opd' => User::where('role', 'opd')->count(), // 🔥 Cuma hitung OPD
        'total_download' => Dataset::where('status', 'approved')->sum('downloads'),
    ];

    // Filter Dropdown Organisasi agar Kadis tidak muncul
    $organizations = User::where('role', 'opd')->has('datasets')->orderBy('name', 'asc')->get(); 
    $years = Dataset::where('status', 'approved')->select('year_start')->distinct()->orderBy('year_start', 'desc')->pluck('year_start');

    // Query publik hanya mengambil status APPROVED
    $query = Dataset::where('status', 'approved');
    
    if (request('search')) {
        $query->where('name', 'like', '%' . request('search') . '%');
    }
    
    if (request('org')) { $query->where('user_id', request('org')); }
    if (request('year')) { $query->where('year_start', request('year')); }

    $latest_datasets = $query->latest()->paginate(10);

    return view('dataset.datasets', compact('stats', 'latest_datasets', 'organizations', 'years'));
})->name('public.datasets');

Route::get('/datasets/{id}', [DatasetController::class, 'show'])->name('public.datasets.show');
Route::get('/datasets/download/{id}', [DatasetController::class, 'download'])->name('public.datasets.download');

Route::get('/organizations', function () {
    // Filter Role OPD & Hitung organisasi berdasarkan data yang APPROVED
    $query = \App\Models\User::where('role', 'opd') // FILTER UTAMA
        ->withCount(['datasets' => function($q) {
            $q->where('status', 'approved');
        }]);

    if (request()->has('search') && request('search') != '') {
        $query->where('name', 'like', '%' . request('search') . '%');
    }

    $organizations = $query->orderBy('name', 'asc')->get();
    return view('organizations.organizations', compact('organizations'));
})->name('public.organizations');

Route::get('/organizations/{id}', [App\Http\Controllers\DatasetController::class, 'organizationDetail'])->name('public.organizations.detail');

Route::get('/groups', function () { 
    return view('groups'); 
})->name('public.groups');

Route::get('/about', function () {
    $stats = [
        'total_dataset'  => Dataset::where('status', 'approved')->count(),
        'organisasi_opd' => User::where('role', 'opd')->count(), // 🔥 Cuma hitung OPD
        'total_download' => Dataset::where('status', 'approved')->sum('downloads'),
    ];
    return view('about', compact('stats'));
})->name('public.about');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DatasetController::class, 'dashboard'])->name('dashboard');
    Route::get('/dataset/download/{id}', [DatasetController::class, 'download'])->name('dataset.download');
    Route::get('/upload-dataset', [DatasetController::class, 'create'])->name('upload.dataset');
    Route::post('/upload-dataset/store', [DatasetController::class, 'store'])->name('upload.dataset.store');
    
    Route::get('/metadata', [MetadataController::class, 'index'])->name('metadata');
    Route::get('/metadata/{id}/edit', [MetadataController::class, 'edit'])->name('metadata.edit');
    Route::post('/metadata/{id}/update', [MetadataController::class, 'update'])->name('metadata.update');
    
    Route::get('/manage-dataset', [DatasetController::class, 'index'])->name('manage-dataset');
    Route::get('/manage-dataset/{id}/edit', [DatasetController::class, 'edit'])->name('upload.dataset.edit');
    Route::put('/manage-dataset/{id}/update', [DatasetController::class, 'update'])->name('upload.dataset.update');
    Route::delete('/manage-dataset/{id}/destroy', [DatasetController::class, 'destroy'])->name('upload.dataset.destroy');
    
    // RUTE VERIFIKASI PIMPINAN
    Route::put('/manage-dataset/{id}/verify', [DatasetController::class, 'verify'])->name('dataset.verify');
    
    Route::get('/organizations-profile', [ProfileController::class, 'index'])->name('organizations');
    Route::get('/organizations-profile/edit', [ProfileController::class, 'edit'])->name('organizations.edit');
    Route::post('/organizations-profile/update', [ProfileController::class, 'update_organization'])->name('organizations.update');

    // RUTE MANAJEMEN AKUN
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}/update', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}/destroy', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';