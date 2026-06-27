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
    $allDatasets = Dataset::where('status', 'approved')->get();
    $totalDatasets = $allDatasets->count();
    $totalPercentage = 0;

    if ($totalDatasets > 0) {
        foreach ($allDatasets as $data) {
            $filledFields = 0;
            if (!empty($data->dssd_code)) $filledFields++;
            if (!empty($data->description)) $filledFields++;
            if (!empty($data->tags)) $filledFields++;
            if (!empty($data->unit)) $filledFields++;
            if (!empty($data->frequency)) $filledFields++;

            $totalPercentage += ($filledFields / 5) * 100;
        }
        $avgVerified = round($totalPercentage / $totalDatasets);
    } else {
        $avgVerified = 0;
    }

    $stats = [
        'total_dataset'      => $totalDatasets,
        'organisasi_opd'     => User::whereNotNull('opd_name')
                                    ->where('opd_name', '!=', '')
                                    ->distinct('opd_name')
                                    ->count('opd_name'),
        'total_download'     => Dataset::where('status', 'approved')->sum('downloads'),
        'data_terverifikasi' => $avgVerified 
    ];

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

    $topOpdsForChart = User::where('role', 'opd')
        ->withCount(['datasets' => function($query) {
            $query->where('status', 'approved');
        }])->orderBy('datasets_count', 'desc')->take(5)->get();
    
    $opdNames = $topOpdsForChart->pluck('name')->map(function($name) {
        return \Illuminate\Support\Str::limit($name, 15); 
    })->toArray();

    $opdCounts = $topOpdsForChart->pluck('datasets_count')->toArray();
    $insightOpd = $topOpdsForChart->first()->name ?? 'Belum ada data';

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

    $trendData = Dataset::where('status', 'approved')
        ->select('year_start', DB::raw('count(*) as total'))
        ->whereNotNull('year_start')
        ->groupBy('year_start')
        ->orderBy('year_start', 'asc')
        ->take(5)
        ->get();

    $trendYears = $trendData->pluck('year_start')->toArray();
    $trendCounts = $trendData->pluck('total')->toArray();

    $kecamatanDataMap = User::where('role', 'opd')
        ->where('name', 'like', '%Kecamatan%')
        ->with('datasets')
        ->get()
        ->mapWithKeys(function ($item) {
            $name = strtoupper($item->name);
            $cleanName = trim(str_replace('KECAMATAN ', '', str_replace('KECAMATAN', '', $name)));
            
            $datasets = $item->datasets;
            $count = $datasets->count();
            $vol2022 = $datasets->where('year_start', 2022)->count();
            $vol2024 = $datasets->where('year_start', 2024)->count();
            $layanan = $vol2024;
            $growth = ($vol2022 > 0) ? (($vol2024 - $vol2022) / $vol2022) * 100 : 0;
            $pertumbuhan = round($growth, 1) . '%';

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
        'organisasi_opd' => User::where('role', 'opd')->count(),
        'total_download' => Dataset::where('status', 'approved')->sum('downloads'),
    ];

    $organizations = User::where('role', 'opd')->has('datasets')->orderBy('name', 'asc')->get(); 
    $years = Dataset::where('status', 'approved')->select('year_start')->distinct()->orderBy('year_start', 'desc')->pluck('year_start');

    $query = Dataset::where('status', 'approved');
    
    if (request('q')) {
        $search = request('q');
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%')
              ->orWhere('dssd_code', 'like', '%' . $search . '%')
              ->orWhere('tags', 'like', '%' . $search . '%')
              ->orWhere('unit', 'like', '%' . $search . '%');
        });
    }
    
    if (request('org')) {
        $query->where('user_id', request('org'));
    }

    if (request('year')) {
        $query->where('year_start', request('year'));
    }

    // TAMBAHAN FILTER FORMAT FILE
    if (request('format')) {
        $query->where('file_path', 'like', '%.' . request('format'));
    }

    // TAMBAHAN FILTER STATUS METADATA
    if (request('metadata_status')) {
        if (request('metadata_status') === 'lengkap') {
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
        }

        if (request('metadata_status') === 'belum_lengkap') {
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

    // TAMBAHAN URUTKAN
    if (request('sort') === 'terlama') {
        $query->oldest();
    } elseif (request('sort') === 'download') {
        $query->orderByDesc('downloads');
    } else {
        $query->latest();
    }

    $latest_datasets = $query->paginate(10)->withQueryString();
    $suggestedDatasets = collect();

if (request('q') && $latest_datasets->total() == 0) {
    $keyword = request('q');
    $prefix = substr($keyword, 0, 3);

    $suggestedDatasets = Dataset::where('status', 'approved')
        ->where(function($q) use ($prefix) {
            $q->where('name', 'like', '%' . $prefix . '%')
              ->orWhere('description', 'like', '%' . $prefix . '%')
              ->orWhere('dssd_code', 'like', '%' . $prefix . '%')
              ->orWhere('tags', 'like', '%' . $prefix . '%')
              ->orWhere('unit', 'like', '%' . $prefix . '%');
        })
        ->latest()
        ->take(3)
        ->get();
}

    return view('dataset.datasets', compact('stats', 'latest_datasets', 'organizations', 'years', 'suggestedDatasets'));
})->name('public.datasets');


// TAMBAHAN API JSON DATASET
Route::get('/api/datasets', [DatasetController::class, 'apiIndex'])->name('api.datasets');


Route::get('/datasets/{id}', [DatasetController::class, 'show'])->name('public.datasets.show');
Route::get('/datasets/download/{id}', [DatasetController::class, 'download'])->name('public.datasets.download');

Route::get('/organizations', function () {
    $query = \App\Models\User::where('role', 'opd')
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
        'organisasi_opd' => User::where('role', 'opd')->count(),
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
    
    Route::put('/manage-dataset/{id}/verify', [DatasetController::class, 'verify'])->name('dataset.verify');
    
    Route::get('/organizations-profile', [ProfileController::class, 'index'])->name('organizations');
    Route::get('/organizations-profile/edit', [ProfileController::class, 'edit'])->name('organizations.edit');
    Route::post('/organizations-profile/update', [ProfileController::class, 'update_organization'])->name('organizations.update');

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