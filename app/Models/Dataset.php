<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dataset extends Model
{
    // Sesuaikan fillable dengan kolom di database Anda
    protected $fillable = [
        'dssd_code', 
        'name', 
        'description', 
        'file_path', 
        'category', 
        'organization', 
        'tags', 
        'unit', 
        'frequency', 
        'level', 
        'year_start', 
        'year_end', 
        'user_id', 
        'downloads',
        'status',
        'feedback'
    ];

    /**
     * Relasi ke User (Organisasi)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}